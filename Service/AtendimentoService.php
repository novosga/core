<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Service;

use PDO;
use DateTime;
use Exception;
use Doctrine\DBAL\LockMode;
use Doctrine\Common\Persistence\ObjectManager;
use Novosga\Entity\Atendimento;
use Novosga\Entity\AtendimentoMeta;
use Novosga\Entity\AtendimentoCodificado;
use Novosga\Entity\AtendimentoHistorico;
use Novosga\Entity\AtendimentoHistoricoMeta;
use Novosga\Entity\AtendimentoCodificadoHistorico;
use Novosga\Entity\Cliente;
use Novosga\Entity\Contador;
use Novosga\Entity\PainelSenha;
use Novosga\Entity\Prioridade;
use Novosga\Entity\Servico;
use Novosga\Entity\Unidade;
use Novosga\Entity\Usuario;
use Psr\Log\LoggerInterface;

/**
 * AtendimentoService.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class AtendimentoService extends MetaModelService
{
    // estados do atendimento
    const SENHA_EMITIDA         = 'emitida';
    const CHAMADO_PELA_MESA     = 'chamado';
    const ATENDIMENTO_INICIADO  = 'iniciado';
    const ATENDIMENTO_ENCERRADO = 'encerrado';
    const NAO_COMPARECEU        = 'nao_compareceu';
    const SENHA_CANCELADA       = 'cancelada';
    const ERRO_TRIAGEM          = 'erro_triagem';
    
    /**
     * @var Dispatcher
     */
    private $dispatcher;
    
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    public function __construct(ObjectManager $em, Dispatcher $dispatcher, LoggerInterface $logger)
    {
        parent::__construct($em);
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
    }
    
    public static function situacoes()
    {
        return [
            self::SENHA_EMITIDA          => _('Senha emitida'),
            self::CHAMADO_PELA_MESA      => _('Chamado pela mesa'),
            self::ATENDIMENTO_INICIADO   => _('Atendimento iniciado'),
            self::ATENDIMENTO_ENCERRADO  => _('Atendimento encerrado'),
            self::NAO_COMPARECEU         => _('Não compareceu'),
            self::SENHA_CANCELADA        => _('Senha cancelada'),
            self::ERRO_TRIAGEM           => _('Erro triagem'),
        ];
    }

    public static function nomeSituacao($status)
    {
        $arr = self::situacoes();

        return $arr[$status];
    }

    protected function getMetaClass()
    {
        return AtendimentoMeta::class;
    }

    protected function getMetaFieldname()
    {
        return 'atendimento';
    }

    /**
     * Cria ou retorna um metadado do atendimento caso o $value seja null (ou ocultado).
     *
     * @param Atendimento $atendimento
     * @param string      $name
     * @param string      $value
     *
     * @return AtendimentoMeta
     */
    public function meta(Atendimento $atendimento, $name, $value = null)
    {
        return $this->modelMetadata($atendimento, $name, $value);
    }

    /**
     * Adiciona uma nova senha na fila de chamada do painel de senhas.
     *
     * @param Unidade     $unidade
     * @param Atendimento $atendimento
     */
    public function chamarSenha(Unidade $unidade, Atendimento $atendimento)
    {
        $senha = new PainelSenha();
        $senha->setUnidade($unidade);
        $senha->setServico($atendimento->getServicoUnidade()->getServico());
        $senha->setNumeroSenha($atendimento->getSenha()->getNumero());
        $senha->setSiglaSenha($atendimento->getSenha()->getSigla());
        $senha->setMensagem($atendimento->getServicoUnidade()->getMensagem() . '');
        // local
        $senha->setLocal($atendimento->getServicoUnidade()->getLocal()->getNome());
        $senha->setNumeroLocal($atendimento->getLocal());
        // prioridade
        $senha->setPeso($atendimento->getPrioridade()->getPeso());
        $senha->setPrioridade($atendimento->getPrioridade()->getNome());
        // cliente
        $senha->setNomeCliente($atendimento->getCliente()->getNome());
        $senha->setDocumentoCliente($atendimento->getCliente()->getDocumento());

        $this->dispatcher->dispatch('panel.pre-call', [$atendimento, $senha]);

        $this->em->persist($senha);
        $this->em->flush();

        $this->dispatcher->dispatch('panel.call', [$atendimento, $senha]);
    }

    /**
     * Move os registros da tabela atendimento para a tabela de historico de atendimentos.
     * Se a unidade não for informada, será acumulado serviços de todas as unidades.
     *
     * @param Unidade|int $unidade
     *
     * @throws Exception
     */
    public function acumularAtendimentos($unidade = 0)
    {
        if ($unidade instanceof Unidade) {
            $unidadeId = $unidade->getId();
        } else {
            $unidadeId = max($unidade, 0);
            $unidade = ($unidadeId > 0) ? $this->em->find('Novosga\Entity\Unidade', $unidadeId) : null;
        }

        $this->dispatcher->dispatch('attending.pre-reset', $unidade);

        $data = (new \DateTime())->format('Y-m-d H:i:s');

        // tables name
        $historicoTable        = $this->em->getClassMetadata(AtendimentoHistorico::class)->getTableName();
        $historicoCodifTable   = $this->em->getClassMetadata(AtendimentoCodificadoHistorico::class)->getTableName();
        $historicoMetaTable    = $this->em->getClassMetadata(AtendimentoHistoricoMeta::class)->getTableName();
        $atendimentoTable      = $this->em->getClassMetadata(Atendimento::class)->getTableName();
        $atendimentoCodifTable = $this->em->getClassMetadata(AtendimentoCodificado::class)->getTableName();
        $atendimentoMetaTable  = $this->em->getClassMetadata(AtendimentoMeta::class)->getTableName();
        $contadorTable         = $this->em->getClassMetadata(Contador::class)->getTableName();
        $painelSenhaTable      = $this->em->getClassMetadata(PainelSenha::class)->getTableName();
        $servicoUnidadeTable   = $this->em->getClassMetadata(\Novosga\Entity\ServicoUnidade::class)->getTableName();
        
        $conn = $this->em->getConnection();
        $conn->beginTransaction();

        try {
            $conn->exec('SET foreign_key_checks = 0');
            
            // copia os atendimentos para o historico
            $sql = "
                INSERT INTO {$historicoTable}
                (
                    id, unidade_id, usuario_id, servico_id, prioridade_id, status,
                    senha_sigla, senha_numero, cliente_id, num_local, dt_cheg,
                    dt_cha, dt_ini, dt_fim, usuario_tri_id, atendimento_id
                )
                SELECT
                    a.id, a.unidade_id, a.usuario_id, a.servico_id, a.prioridade_id, a.status,
                    a.senha_sigla, a.senha_numero, a.cliente_id, a.num_local, a.dt_cheg,
                    a.dt_cha, a.dt_ini, a.dt_fim, a.usuario_tri_id, a.atendimento_id
                FROM
                    {$atendimentoTable} a
                WHERE
                    a.dt_cheg <= :data AND (a.unidade_id = :unidade OR :unidade = 0)
            ";

            // atendimentos pais (nao oriundos de redirecionamento)
            $query = $conn->prepare("$sql AND a.atendimento_id IS NULL");
            $query->bindValue('data', $data, PDO::PARAM_STR);
            $query->bindValue('unidade', $unidadeId, PDO::PARAM_INT);
            $query->execute();

            // atendimentos filhos (oriundos de redirecionamento)
            $query = $conn->prepare("{$sql} AND a.atendimento_id IS NOT NULL");
            $query->bindValue('data', $data, PDO::PARAM_STR);
            $query->bindValue('unidade', $unidadeId, PDO::PARAM_INT);
            $query->execute();

            // copia os metadados
            $sql = "
                INSERT INTO $historicoMetaTable
                (
                    atendimento_id, name, value
                )
                SELECT
                    a.atendimento_id, a.name, a.value
                FROM
                    {$atendimentoMetaTable}  a
                WHERE
                    a.atendimento_id IN (
                        SELECT b.id
                        FROM {$atendimentoTable} b
                        WHERE
                            b.dt_cheg <= :data AND
                            (b.unidade_id = :unidade OR :unidade = 0)
                    )
            ";
            $query = $conn->prepare($sql);
            $query->bindValue('data', $data, PDO::PARAM_STR);
            $query->bindValue('unidade', $unidadeId, PDO::PARAM_INT);
            $query->execute();

            // copia os atendimentos codificados para o historico
            $query = $conn->prepare("
                INSERT INTO $historicoCodifTable 
                (
                    atendimento_id, servico_id, valor_peso
                )
                SELECT
                    ac.atendimento_id, ac.servico_id, ac.valor_peso
                FROM
                    {$atendimentoCodifTable} ac
                    JOIN {$atendimentoTable} a ON a.id = ac.atendimento_id
                WHERE
                    a.dt_cheg <= :data AND 
                    (a.unidade_id = :unidade OR :unidade = 0)
            ");
            $query->bindValue('data', $data, PDO::PARAM_STR);
            $query->bindValue('unidade', $unidadeId, PDO::PARAM_INT);
            $query->execute();

            // limpa atendimentos codificados
            $query = $conn->prepare("
                DELETE FROM {$atendimentoCodifTable}
                WHERE atendimento_id IN (
                    SELECT id
                    FROM {$atendimentoTable}
                    WHERE
                        dt_cheg <= :data AND
                        (unidade_id = :unidade OR :unidade = 0)
                )
            ");
            $query->bindValue('data', $data, PDO::PARAM_STR);
            $query->bindValue('unidade', $unidadeId, PDO::PARAM_INT);
            $query->execute();

            // limpa metadata
            $query = $conn->prepare("
                DELETE FROM {$atendimentoMetaTable}
                WHERE atendimento_id IN (
                    SELECT id
                    FROM {$atendimentoTable}
                    WHERE
                        dt_cheg <= :data AND
                        (unidade_id = :unidade OR :unidade = 0)
                )
            ");
            $query->bindValue('data', $data, PDO::PARAM_STR);
            $query->bindValue('unidade', $unidadeId, PDO::PARAM_INT);
            $query->execute();

            // limpa o auto-relacionamento para poder excluir os atendimento sem dar erro de constraint (#136)
            $query = $conn->prepare("
                DELETE FROM {$atendimentoTable}
                WHERE
                    atendimento_id IS NOT NULL AND
                    dt_cheg <= :data AND
                    (unidade_id = :unidade OR :unidade = 0)
            ");
            $query->bindValue('data', $data, PDO::PARAM_STR);
            $query->bindValue('unidade', $unidadeId, PDO::PARAM_INT);
            $query->execute();

            // limpa atendimentos da unidade
            $query = $conn->prepare("
                DELETE FROM {$atendimentoTable}
                WHERE 
                    dt_cheg <= :data AND
                    (unidade_id = :unidade OR :unidade = 0)
            ");
            $query->bindValue('data', $data, PDO::PARAM_STR);
            $query->bindValue('unidade', $unidadeId, PDO::PARAM_INT);
            $query->execute();

            // limpa a tabela de senhas a serem exibidas no painel
            $query = $conn->prepare("
                DELETE FROM {$painelSenhaTable}
                WHERE (unidade_id = :unidade OR :unidade = 0)
            ");
            $query->bindValue('unidade', $unidadeId, PDO::PARAM_INT);
            $query->execute();

            // reinicia o contador das senhas
            $query = $conn->prepare("
                UPDATE {$contadorTable}
                SET numero = (
                    SELECT su.numero_inicial
                    FROM {$servicoUnidadeTable} su
                    WHERE
                        su.unidade_id = {$contadorTable}.unidade_id AND
                        su.servico_id = {$contadorTable}.servico_id
                )
                WHERE (unidade_id = :unidade OR :unidade = 0)
            ");
            $query->bindValue('unidade', $unidadeId, PDO::PARAM_INT);
            $query->execute();

            $conn->commit();
        } catch (Exception $e) {
            try {
                $conn->rollBack();
            } catch (Exception $e2) {
            }
            throw $e;
        }

        $this->dispatcher->dispatch('attending.reset', $unidade);
    }

    public function buscaAtendimento(Unidade $unidade, $id)
    {
        $query = $this->em->createQuery("
            SELECT e
            FROM Novosga\Entity\Atendimento e
            JOIN e.servicoUnidade su
            WHERE
                e.id = :id AND
                su.unidade = :unidade
        ");
        $query->setParameter('id', (int) $id);
        $query->setParameter('unidade', $unidade->getId());

        return $query->getOneOrNullResult();
    }

    public function buscaAtendimentos(Unidade $unidade, $senha)
    {
        $i = 0;
        $sigla = '';
        do {
            $char = substr($senha, $i, 1);
            $isAlpha = ctype_alpha($char);
            if ($isAlpha) {
                $sigla .= strtoupper($char);
            }
            $i++;
        } while ($i < strlen($senha) && $isAlpha);
        
        $numero = (int) substr($senha, $i - 1);
        
        $rs = $this->em
                ->createQueryBuilder()
                ->select([
                    'e', 'su', 's', 'ut', 'u'
                ])
                ->from(Atendimento::class, 'e')
                ->join('e.servicoUnidade', 'su')
                ->join('e.servico', 's')
                ->join('e.usuarioTriagem', 'ut')
                ->leftJoin('e.usuario', 'u')
                ->where(':numero = 0 OR e.senha.numero = :numero')
                ->andWhere(':sigla IS NULL OR e.senha.sigla = :sigla')
                ->andWhere('su.unidade = :unidade')
                ->orderBy('e.id', 'ASC')
                ->setParameters([
                    'numero' => $numero,
                    'sigla' => empty($sigla) ? null : $sigla,
                    'unidade' => $unidade->getId()
                ])
                ->getQuery()
                ->getResult();
        
        return $rs;
    }

    public function chamar(Atendimento $atendimento, Usuario $usuario, $local)
    {
        $this->dispatcher->dispatch('attending.pre-call', [$atendimento, $usuario, $local]);

        $this->em->getConnection()->beginTransaction();

        try {
            $this->em->lock($atendimento, LockMode::PESSIMISTIC_WRITE);

            $atendimento->setUsuario($usuario);
            $atendimento->setLocal($local);
            $atendimento->setStatus(self::CHAMADO_PELA_MESA);
            $atendimento->setDataChamada(new DateTime());

            $this->em->merge($atendimento);
            $this->em->getConnection()->commit();
            $this->em->flush();

            $this->dispatcher->dispatch('attending.call', [$atendimento, $usuario]);
        } catch (Exception $e) {
            $this->em->getConnection()->rollback();

            return false;
        }

        return true;
    }

    /**
     * Retorna o atendimento em andamento do usuario informado.
     *
     * @param int|Usuario $usuario
     * @param int|Unidade $unidade
     *
     * @return Atendimento
     */
    public function atendimentoAndamento($usuario, $unidade = null)
    {
        $status = [
            self::CHAMADO_PELA_MESA,
            self::ATENDIMENTO_INICIADO,
        ];
        try {
            $qb = $this->em
                ->createQueryBuilder()
                ->select('e')
                ->from(Atendimento::class, 'e')
                ->where('e.usuario = :usuario')
                ->andWhere('e.status IN (:status)');
            
            $params = [
                'usuario' => $usuario,
                'status' => $status,
            ];
            
            if ($unidade) {
                $qb->andWhere('e.unidade = :unidade');
                $params['unidade'] = $unidade;
            }
            
            return $qb
                ->setParameters($params)
                ->getQuery()
                ->getOneOrNullResult();
        } catch (\Doctrine\ORM\NonUniqueResultException $e) {
            /*
             * caso tenha mais de um atendimento preso ao usuario,
             * libera os atendimentos e retorna null para o atendente chamar de novo.
             * BUG #213
             */
            $this->em
                ->createQuery('
                    UPDATE Novosga\Entity\Atendimento e
                    SET 
                        e.status = 1,
                        e.usuario = NULL
                    WHERE
                        e.usuario = :usuario AND
                        e.status IN (:status)
                ')
                ->setParameter('usuario', $usuario)
                ->setParameter('status', $status)
                ->execute();

            return;
        }
    }

    /**
     * Gera um novo atendimento.
     *
     * @param int|Unidade    $unidade
     * @param int|Usuario    $usuario
     * @param int|Servico    $servico
     * @param int|Prioridade $prioridade
     * @param string         $nomeCliente
     * @param string         $documentoCliente
     *
     * @throws Exception
     *
     * @return Atendimento
     */
    public function distribuiSenha($unidade, $usuario, $servico, $prioridade, Cliente $cliente = null)
    {
        // verificando a unidade
        if (!($unidade instanceof Unidade)) {
            $unidade = $this->em->find(Unidade::class, $unidade);
        }
        if (!$unidade) {
            throw new Exception(_('Nenhum unidade escolhida'));
        }
        // verificando o usuario na sessao
        if (!($usuario instanceof Usuario)) {
            $usuario = $this->em->find(Usuario::class, $usuario);
        }
        if (!$usuario) {
            throw new Exception(_('Nenhum usuário na sessão'));
        }
        // verificando o servico
        if (!($servico instanceof Servico)) {
            $servico = $this->em->find(Servico::class, $servico);
        }
        if (!$servico) {
            throw new Exception(_('Serviço inválido'));
        }
        // verificando a prioridade
        if (!($prioridade instanceof Prioridade)) {
            $prioridade = $this->em->find(Prioridade::class, $prioridade);
        }
        if (!$prioridade || !$prioridade->isAtivo()) {
            throw new Exception(_('Prioridade inválida'));
        }
        
        /*
         * TODO: validar unidade x usuario x servico
         */
        
        // verificando se o cliente ja existe
        if ($cliente) {
            $clienteExistente = null;
            $clienteRepository = $this->em->getRepository(Cliente::class);
            
            if ($cliente->getId()) {
                $clienteExistente = $clienteRepository->find($cliente->getId());
            }
            
            if (!$clienteExistente && $cliente->getEmail()) {
                $clienteExistente = $clienteRepository->findOneBy(['email' => $cliente->getEmail()]);
            }
            
            if (!$clienteExistente && $cliente->getDocumento()) {
                $clienteExistente = $clienteRepository->findOneBy(['documento' => $cliente->getDocumento()]);
            }
            
            if ($clienteExistente) {
                $cliente = $clienteExistente;
            }
        }

        // verificando se o servico esta disponivel na unidade
        $service = new ServicoService($this->em);
        $su = $service->servicoUnidade($unidade, $servico);
        
        if (!$su) {
            throw new Exception(_('Serviço não disponível para a unidade atual'));
        }
        
        $atendimento = new Atendimento();
        $atendimento->setServicoUnidade($su);
        $atendimento->setPrioridade($prioridade);
        $atendimento->setUsuarioTriagem($usuario);
        $atendimento->setStatus(self::SENHA_EMITIDA);
        $atendimento->setLocal(null);
        $atendimento->getSenha()->setSigla($su->getSigla());

        if ($cliente) {
            $atendimento->setCliente($cliente);
        }

        $this->dispatcher->dispatch('attending.pre-create', [$atendimento]);
        
        $conn = $this->em->getConnection();
        $contadorTable = $this->em->getClassMetadata(Contador::class)->getTableName();
        
        try {
            $stmt = $conn->prepare("
                SELECT numero 
                FROM {$contadorTable} 
                WHERE
                    unidade_id = :unidade AND
                    servico_id = :servico
                FOR UPDATE
            ");
            $stmt->bindValue('unidade', $unidade->getId());
            $stmt->bindValue('servico', $servico->getId());
            $stmt->execute();
            $numeroAtual = (int) $stmt->fetchColumn();
            $numeroSenha = $numeroAtual;
            
            if (!$numeroAtual) {
                throw new Exception();
            }
            
            $numeroSenha += $su->getIncremento();
            if ($su->getNumeroFinal() > 0 && $numeroSenha > $su->getNumeroFinal()) {
                $numeroSenha = $su->getNumeroInicial();
            }

            $stmt = $conn->prepare("
                UPDATE {$contadorTable} 
                SET numero = :numero
                WHERE
                    unidade_id = :unidade AND
                    servico_id = :servico AND
                    numero = :numeroAtual
            ");
            $stmt->bindValue('numero', $numeroSenha);
            $stmt->bindValue('unidade', $unidade->getId());
            $stmt->bindValue('servico', $servico->getId());
            $stmt->bindValue('numeroAtual', $numeroAtual);
            $stmt->execute();
            $success = $stmt->rowCount() === 1;
            
            if (!$success) {
                throw new Exception();
            }
            
            $atendimento->setDataChegada(new DateTime());
            $atendimento->getSenha()->setNumero($numeroSenha);

            $this->em->persist($atendimento);
            $this->em->flush();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
        
        if (!$atendimento->getId()) {
            throw new Exception(_('Erro ao tentar gerar nova senha'));
        }
        
        return $atendimento;
    }

    /**
     * Redireciona um atendimento para outro servico.
     *
     * @param Atendimento $atendimento
     * @param Usuario     $usuario
     * @param int|Unidade $unidade
     * @param int|Servico $servico
     *
     * @return Atendimento
     */
    public function redirecionar(Atendimento $atendimento, Usuario $usuario, $unidade, $servico)
    {
        // copiando a senha do atendimento atual
        $service = new ServicoService($this->em);
        $su = $service->servicoUnidade($unidade, $servico);

        $this->dispatcher->dispatch('attending.pre-redirect', [$atendimento, $su, $usuario]);

        $novo = new Atendimento();
        $novo->setLocal(null);
        $novo->setServicoUnidade($su);
        $novo->setPai($atendimento);
        $novo->setDataChegada(new DateTime());
        $novo->setStatus(self::SENHA_EMITIDA);
        $novo->getSenha()->setSigla($atendimento->getSenha()->getSigla());
        $novo->getSenha()->setNumero($atendimento->getSenha()->getNumero());
        $novo->setUsuario($usuario);
        $novo->setUsuarioTriagem($usuario);
        $novo->setPrioridade($atendimento->getPrioridade());
        $novo->setCliente($atendimento->getCliente());
        
        $this->em->persist($novo);
        $this->em->flush();

        $this->dispatcher->dispatch('attending.redirect', $atendimento);

        return $novo;
    }

    /**
     * Transfere o atendimento para outro serviço e prioridade.
     *
     * @param Atendimento    $atendimento
     * @param Unidade        $unidade
     * @param int|Servico    $novoServico
     * @param int|Prioridade $novaPrioridade
     *
     * @return bool
     */
    public function transferir(Atendimento $atendimento, Unidade $unidade, $novoServico, $novaPrioridade)
    {
        $this->dispatcher->dispatch('attending.pre-transfer', $atendimento, $unidade, $novoServico, $novaPrioridade);

        // transfere apenas se a data fim for nula (nao finalizados)
        $success = $this->em->createQuery('
            UPDATE
                Novosga\Entity\Atendimento e
            SET
                e.servico = :servico,
                e.prioridade = :prioridade
            WHERE
                e.id = :id AND
                e.unidade = :unidade AND
                e.dataFim IS NULL
            ')
            ->setParameter('servico', $novoServico)
            ->setParameter('prioridade', $novaPrioridade)
            ->setParameter('id', $atendimento)
            ->setParameter('unidade', $unidade)
            ->execute() > 0;

        if ($success) {
            $this->em->refresh($atendimento);
            $this->dispatcher->dispatch('attending.transfer', [$atendimento]);
        }

        return $success;
    }

    /**
     * Atualiza o status da senha para cancelado.
     *
     * @param Atendimento $atendimento
     * @param Unidade     $unidade
     *
     * @return bool
     */
    public function cancelar(Atendimento $atendimento, Unidade $unidade)
    {
        $this->dispatcher->dispatch('attending.pre-cancel', $atendimento);

        // cancela apenas se a data fim for nula
        $success = $this->em->createQuery('
            UPDATE
                Novosga\Entity\Atendimento e
            SET
                e.status = :status,
                e.dataFim = :data
            WHERE
                e.id = :id AND
                e.unidade = :unidade AND
                e.dataFim IS NULL
            ')
            ->setParameter('status', self::SENHA_CANCELADA)
            ->setParameter('data', new DateTime())
            ->setParameter('id', $atendimento)
            ->setParameter('unidade', $unidade)
            ->execute() > 0;

        if ($success) {
            $this->em->refresh($atendimento);
            $this->dispatcher->dispatch('attending.cancel', $atendimento);
        }

        return $success;
    }

    /**
     * Reativa o atendimento para o mesmo serviço e mesma prioridade.
     * Só pode reativar atendimentos que foram: Cancelados ou Não Compareceu.
     *
     * @param Atendimento $atendimento
     * @param Unidade     $unidade
     *
     * @return bool
     */
    public function reativar(Atendimento $atendimento, Unidade $unidade)
    {
        $this->dispatcher->dispatch('attending.pre-reactivate', $atendimento);

        // reativa apenas se estiver finalizada (data fim diferente de nulo)
        $success = $this->em->createQuery('
            UPDATE
                Novosga\Entity\Atendimento e
            SET
                e.status = :status,
                e.dataFim = NULL
            WHERE
                e.id = :id AND
                e.unidade = :unidade AND
                e.status IN (:statuses)
            ')
            ->setParameter('status', self::SENHA_EMITIDA)
            ->setParameter('statuses', [self::SENHA_CANCELADA, self::NAO_COMPARECEU])
            ->setParameter('id', $atendimento)
            ->setParameter('unidade', $unidade)
            ->execute() > 0;

        if ($success) {
            $this->em->refresh($atendimento);
            $this->dispatcher->dispatch('attending.reactivate', $atendimento);
        }

        return $success;
    }
    
    public function encerrar(
        Atendimento $atendimento,
        Unidade $unidade,
        Usuario $usuario,
        array $servicos,
        $servicoRedirecionado = null
    ) {
        if ($atendimento->getStatus() !== AtendimentoService::ATENDIMENTO_INICIADO) {
            throw new Exception(
                sprintf(
                    'Erro ao tentar encerrar um atendimento nao iniciado (%s)',
                    $atendimento->getId()
                )
            );
        }
        
        $this->em->beginTransaction();
        
        try {
            foreach ($servicos as $s) {
                if ($s instanceof Servico) {
                    $servico = $s;
                } else {
                    $servico = $this->em->find('Novosga\Entity\Servico', $s);
                }

                if (!$servico) {
                    throw new Exception(_('Serviço inválido'));
                }

                $codificado = new \Novosga\Entity\AtendimentoCodificado();
                $codificado->setAtendimento($atendimento);
                $codificado->setServico($servico);
                $codificado->setPeso(1);
                $this->em->persist($codificado);
            }
            // verifica se esta encerrando e redirecionando
            if ($servicoRedirecionado) {
                $redirecionado = $this->redirecionar($atendimento, $usuario, $unidade, $servicoRedirecionado);
                if (!$redirecionado->getId()) {
                    throw new Exception(
                        sprintf(
                            _('Erro ao redirecionar atendimento %s para o serviço %s'),
                            $atendimento->getId(),
                            $servico
                        )
                    );
                }
            }
            
            $atendimento->setDataFim(new \DateTime);
            $atendimento->setStatus(AtendimentoService::ATENDIMENTO_ENCERRADO);
            $this->em->merge($atendimento);

            $this->em->commit();
            $this->em->flush();
        } catch (Exception $e) {
            try {
                $this->em->rollback();
            } catch (Exception $ex) {
            }
            throw new Exception(sprintf(_('Erro ao encerrar o atendimento %s'), $atendimento->getId()));
        }
    }

    /**
     * Retorna a ultima senha da unidad.
     *
     * @param Unidade|int $unidade
     *
     * @return Atendimento
     */
    public function ultimaSenhaUnidade($unidade)
    {
        return $this->em
            ->createQueryBuilder()
            ->select('e')
            ->from(Atendimento::class, 'e')
            ->join('e.servicoUnidade', 'su')
            ->where('su.unidade = :unidade')
            ->orderBy('e.id', 'DESC')
            ->setParameter('unidade', $unidade)
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
    }

    /**
     * Retorna a ultima senha do servico.
     *
     * @param Unidade|int $unidade
     * @param Servico|int $servico
     *
     * @return Atendimento
     */
    public function ultimaSenhaServico($unidade, $servico)
    {
        $atendimento = $this->em
            ->createQueryBuilder()
            ->select('e')
            ->from(Atendimento::class, 'e')
            ->join('e.servicoUnidade', 'su')
            ->where('su.servico = :servico')
            ->andWhere('su.unidade = :unidade')
            ->orderBy('e.senha.numero', 'DESC')
            ->setParameters([
                'servico' => $servico,
                'unidade' => $unidade
            ])
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();
        
        return $atendimento;
    }
    
    public function alteraStatusAtendimentoUsuario(Usuario $usuario, $novoStatus)
    {
        $atual = $this->atendimentoAndamento($usuario->getId());
            
        if (!$atual) {
            throw new Exception(_('Nenhum atendimento disponível'));
        }
            
        $campoData = null;
        
        switch ($novoStatus) {
            case AtendimentoService::ATENDIMENTO_INICIADO:
                $statusAtual = [ AtendimentoService::CHAMADO_PELA_MESA ];
                $campoData   = 'dataInicio';
                break;
            case AtendimentoService::NAO_COMPARECEU:
                $statusAtual = [ AtendimentoService::CHAMADO_PELA_MESA ];
                $campoData   = 'dataFim';
                break;
            case AtendimentoService::ATENDIMENTO_ENCERRADO:
                $statusAtual = [ AtendimentoService::ATENDIMENTO_INICIADO ];
                $campoData   = 'dataFim';
                break;
            case AtendimentoService::ERRO_TRIAGEM:
                $statusAtual = [
                    AtendimentoService::ATENDIMENTO_INICIADO,
                    AtendimentoService::ATENDIMENTO_ENCERRADO,
                ];
                $campoData   = 'dataFim';
                break;
            default:
                throw new Exception('Novo status inválido.');
        }

        if (!is_array($statusAtual)) {
            $statusAtual = [$statusAtual];
        }

        $data = (new \DateTime())->format('Y-m-d H:i:s');

        $qb = $this->em->createQueryBuilder()
                ->update(Atendimento::class, 'e')
                ->set('e.status', ':novoStatus');
        
        if ($campoData !== null) {
            $qb->set("e.{$campoData}", ':data');
        }
        
        $qb
            ->where('e.id = :id')
            ->andWhere('e.status IN (:statusAtual)');
        
        $params = [
            'novoStatus'  => $novoStatus,
            'id'          => $atual->getId(),
            'statusAtual' => $statusAtual
        ];
        
        if ($campoData !== null) {
            $params['data'] = $data;
        }

        $success = $qb
                ->setParameters($params)
                ->getQuery()
                ->execute() > 0;

        if (!$success) {
            throw new Exception(
                sprintf(
                    _('Erro ao mudar status do atendimento #%s para %s'),
                    $atual->getId(),
                    $novoStatus
                )
            );
        }

        $atual->setStatus($novoStatus);

        return $atual;
    }
}
