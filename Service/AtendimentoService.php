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

use DateTime;
use Exception;
use Novosga\Entity\Agendamento;
use Novosga\Entity\Atendimento;
use Novosga\Entity\AtendimentoCodificado;
use Novosga\Entity\AtendimentoMeta;
use Novosga\Entity\Cliente;
use Novosga\Entity\Lotacao;
use Novosga\Entity\PainelSenha;
use Novosga\Entity\Prioridade;
use Novosga\Entity\Servico;
use Novosga\Entity\ServicoUnidade;
use Novosga\Entity\Unidade;
use Novosga\Entity\Usuario;
use Novosga\Infrastructure\StorageInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * AtendimentoService.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class AtendimentoService extends StorageAwareService
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
     * @var EventDispatcher
     */
    private $dispatcher;
    
    /**
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * @var TranslatorInterface
     */
    private $translator;
    
    public function __construct(
        StorageInterface $storage,
        EventDispatcher $dispatcher,
        LoggerInterface $logger,
        TranslatorInterface $translator
    ) {
        parent::__construct($storage);
        $this->dispatcher = $dispatcher;
        $this->logger     = $logger;
        $this->translator = $translator;
    }
    
    public function situacoes()
    {
        return [
            self::SENHA_EMITIDA          => $this->translator->trans('ticket.status.generated'),
            self::CHAMADO_PELA_MESA      => $this->translator->trans('ticket.status.called'),
            self::ATENDIMENTO_INICIADO   => $this->translator->trans('ticket.status.started'),
            self::ATENDIMENTO_ENCERRADO  => $this->translator->trans('ticket.status.finished'),
            self::NAO_COMPARECEU         => $this->translator->trans('ticket.status.no_show'),
            self::SENHA_CANCELADA        => $this->translator->trans('ticket.status.cancelled'),
            self::ERRO_TRIAGEM           => $this->translator->trans('ticket.status.error'),
        ];
    }

    public function nomeSituacao($status)
    {
        $arr = $this->situacoes();

        return $arr[$status];
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
        $repo = $this->storage->getRepository(AtendimentoMeta::class);
        
        if ($value === null) {
            $metadata = $repo->get($atendimento, $name);
        } else {
            $metadata = $repo->set($atendimento, $name, $value);
        }
        
        return $metadata;
    }

    /**
     * Adiciona uma nova senha na fila de chamada do painel de senhas.
     *
     * @param Unidade     $unidade
     * @param Atendimento $atendimento
     */
    public function chamarSenha(Unidade $unidade, Atendimento $atendimento)
    {
        $servico = $atendimento->getServico();
        
        $su = $this->storage
            ->getRepository(ServicoUnidade::class)
            ->get($unidade, $servico);
        
        $senha = new PainelSenha();
        $senha->setUnidade($unidade);
        $senha->setServico($servico);
        $senha->setNumeroSenha($atendimento->getSenha()->getNumero());
        $senha->setSiglaSenha($atendimento->getSenha()->getSigla());
        $senha->setMensagem($su->getMensagem() . '');
        // local
        $senha->setLocal($su->getLocal()->getNome());
        $senha->setNumeroLocal($atendimento->getLocal());
        // prioridade
        $senha->setPeso($atendimento->getPrioridade()->getPeso());
        $senha->setPrioridade($atendimento->getPrioridade()->getNome());
        // cliente
        if ($atendimento->getCliente()) {
            $senha->setNomeCliente($atendimento->getCliente()->getNome());
            $senha->setDocumentoCliente($atendimento->getCliente()->getDocumento());
        }

        $this->dispatcher->createAndDispatch('panel.pre-call', [$atendimento, $senha], true);
        
        $om = $this->storage->getManager();
        $om->persist($senha);
        $om->flush();

        $this->dispatcher->createAndDispatch('panel.call', [$atendimento, $senha], true);
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
            $unidade   = null;
            if ($unidadeId > 0) {
                $unidade = $this
                    ->storage
                    ->getRepository(Unidade::class)
                    ->find($unidadeId);
            }
        }

        $this->dispatcher->createAndDispatch('attending.pre-reset', $unidade, true);

        $this->storage->acumularAtendimentos($unidade);

        $this->dispatcher->createAndDispatch('attending.reset', $unidade, true);
    }

    public function buscaAtendimento(Unidade $unidade, $id)
    {
        $atendimento = $this->storage
            ->getManager()
            ->createQueryBuilder()
            ->select('e')
            ->from(Atendimento::class, 'e')
            ->where('e.id = :id')
            ->andWhere('e.unidade = :unidade')
            ->setParameters([
                'id'      => (int) $id,
                'unidade' => $unidade->getId()
            ])
            ->getQuery()
            ->getOneOrNullResult();

        return $atendimento;
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
        
        $qb = $this
            ->storage
            ->getManager()
            ->createQueryBuilder()
            ->select([
                'e', 's', 'ut', 'u'
            ])
            ->from(Atendimento::class, 'e')
            ->join('e.servico', 's')
            ->join('e.usuarioTriagem', 'ut')
            ->leftJoin('e.usuario', 'u')
            ->where(':numero = 0 OR e.senha.numero = :numero')
            ->andWhere('e.unidade = :unidade')
            ->orderBy('e.id', 'ASC')
            ->setParameters([
                'numero'  => $numero,
                'unidade' => $unidade->getId(),
            ]);
        
        if (!empty($sigla)) {
            $qb
                ->andWhere('e.senha.sigla = :sigla')
                ->setParameter('sigla', $sigla);
        }
        
        $rs = $qb
            ->getQuery()
            ->getResult();
        
        return $rs;
    }

    public function chamar(Atendimento $atendimento, Usuario $usuario, int $local)
    {
        $this->dispatcher->createAndDispatch('attending.pre-call', [$atendimento, $usuario, $local], true);
        
        $atendimento->setUsuario($usuario);
        $atendimento->setLocal($local);
        $atendimento->setStatus(self::CHAMADO_PELA_MESA);
        $atendimento->setDataChamada(new DateTime());
        
        $tempoEspera = $atendimento->getDataChamada()->diff($atendimento->getDataChegada());
        $atendimento->setTempoEspera($tempoEspera);

        try {
            $this->storage->chamar($atendimento);

            $this->dispatcher->createAndDispatch('attending.call', [$atendimento, $usuario], true);
        } catch (Exception $e) {
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
            $qb = $this->storage
                ->getManager()
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
            $this->storage
                ->getManager()
                ->createQueryBuilder()
                ->update(Atendimento::class, 'e')
                ->set('e.status', ':status')
                ->set('e.usuario', ':null')
                ->where('e.usuario = :usuario')
                ->andWhere('e.status IN (:status)')
                ->setParameters([
                    'status'  => 1,
                    'null'    => null,
                    'usuario' => $usuario,
                    'status'  => $status
                ])
                ->getQuery()
                ->execute();

            return;
        }
    }

    /**
     * Gera um novo atendimento.
     *
     * @param int|Unidade       $unidade
     * @param int|Usuario       $usuario
     * @param int|Servico       $servico
     * @param int|Prioridade    $prioridade
     * @param Cliente|null      $cliente
     * @param Agendamento|null  $agendamento
     *
     * @throws Exception
     *
     * @return Atendimento
     */
    public function distribuiSenha($unidade, $usuario, $servico, $prioridade, Cliente $cliente = null, Agendamento $agendamento = null)
    {
        $om = $this->storage->getManager();
        
        // verificando a unidade
        if (!($unidade instanceof Unidade)) {
            $unidade = $om->find(Unidade::class, $unidade);
        }
        if (!$unidade) {
            $error = $this->translator->trans('error.invalid_unity');
            throw new Exception($error);
        }
        // verificando o usuario na sessao
        if (!($usuario instanceof Usuario)) {
            $usuario = $om->find(Usuario::class, $usuario);
        }
        if (!$usuario) {
            $error = $this->translator->trans('error.invalid_user');
            throw new Exception($error);
        }
        // verificando o servico
        if (!($servico instanceof Servico)) {
            $servico = $om->find(Servico::class, $servico);
        }
        if (!$servico) {
            $error = $this->translator->trans('error.invalid_service');
            throw new Exception($error);
        }
        // verificando a prioridade
        if (!($prioridade instanceof Prioridade)) {
            $prioridade = $om->find(Prioridade::class, $prioridade);
        }
        if (!$prioridade || !$prioridade->isAtivo()) {
            $error = $this->translator->trans('error.invalid_priority');
            throw new Exception($error);
        }
        
        if (!$usuario->isAdmin()) {
            $lotacao = $om
                ->getRepository(Lotacao::class)
                ->findOneBy([
                    'usuario' => $usuario,
                    'unidade' => $unidade,
                ]);

            if (!$lotacao) {
                $error = $this->translator->trans('error.user_unity_ticket_permission');
                throw new Exception($error);
            }
        }
        
        $su = $this->checkServicoUnidade($unidade, $servico);
        
        $atendimento = new Atendimento();
        $atendimento->setServico($servico);
        $atendimento->setUnidade($unidade);
        $atendimento->setPrioridade($prioridade);
        $atendimento->setUsuarioTriagem($usuario);
        $atendimento->setStatus(self::SENHA_EMITIDA);
        $atendimento->setLocal(null);
        $atendimento->getSenha()->setSigla($su->getSigla());
        
        if ($agendamento) {
            $data = $agendamento->getData()->format('Y-m-d');
            $hora = $agendamento->getHora()->format('H:i');
            $dtAge = DateTime::createFromFormat('Y-m-d H:i', "{$data} {$hora}");
            $atendimento->setDataAgendamento($dtAge);
        }
        
        $clienteValido = $this->getClienteValido($cliente);

        if ($clienteValido) {
            $atendimento->setCliente($clienteValido);
        }

        $this->dispatcher->createAndDispatch('attending.pre-create', [$atendimento], true);
        
        try {
            $this->storage->distribui($atendimento, $agendamento);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
        
        if (!$atendimento->getId()) {
            $error = $this->translator->trans('error.new_ticket');
            throw new Exception($error);
        }
        
        return $atendimento;
    }

    /**
     * @param Atendimento $atendimento
     * @param Usuario $usuario
     * @throws Exception
     */
    public function iniciarAtendimento(Atendimento $atendimento, Usuario $usuario)
    {
        $status = $atendimento->getStatus();
        
        if (!in_array($status, [ self::CHAMADO_PELA_MESA ])) {
            throw new Exception('Não pode iniciar esse atendimento.');
        }
        
        $atendimento->setStatus(self::ATENDIMENTO_INICIADO);
        $atendimento->setDataInicio(new DateTime());
        $atendimento->setUsuario($usuario);
        
        $tempoDeslocamento = $atendimento->getDataInicio()->diff($atendimento->getDataChamada());
        
        $atendimento->setTempoDeslocamento($tempoDeslocamento);
        
        $om = $this->storage->getManager();
        $om->merge($atendimento);
        
        $om->flush();
    }

    /**
     * @param Atendimento $atendimento
     * @param Usuario $usuario
     * @throws Exception
     */
    public function naoCompareceu(Atendimento $atendimento, Usuario $usuario)
    {
        $status = $atendimento->getStatus();
        
        if (!in_array($status, [ self::CHAMADO_PELA_MESA ])) {
            throw new Exception('Não pode iniciar esse atendimento.');
        }
        
        $atendimento->setDataFim(new DateTime());
        $atendimento->setStatus(self::NAO_COMPARECEU);
        $atendimento->setUsuario($usuario);
        
        $tempoPermanencia  = $atendimento->getDataFim()->diff($atendimento->getDataChegada());
        $tempoAtendimento  = new \DateInterval('P0M');
        $tempoDeslocamento = new \DateInterval('P0M');
        
        $atendimento->setTempoPermanencia($tempoPermanencia);
        $atendimento->setTempoAtendimento($tempoAtendimento);
        $atendimento->setTempoDeslocamento($tempoDeslocamento);
        
        $om = $this->storage->getManager();
        $om->merge($atendimento);
        
        $om->flush();
    }

    /**
     * Redireciona um atendimento para outro serviço.
     *
     * @param Atendimento $atendimento
     * @param int|Unidade $unidade
     * @param int|Servico $servico
     * @param int|Usuario $usuario Novo usuário a atender o serviço redirecionado (opcional)
     *
     * @return Atendimento
     */
    public function redirecionar(Atendimento $atendimento, $unidade, $servico, $usuario = null)
    {
        $status = $atendimento->getStatus();
        
        if (!in_array($status, [ self::ATENDIMENTO_INICIADO, self::ATENDIMENTO_ENCERRADO ])) {
            throw new Exception('Não pode redirecionar esse atendimento.');
        }
        
        if (!($unidade instanceof Unidade)) {
            $unidade = $this->storage
                ->getRepository(Unidade::class)
                ->find($unidade);
        }
        
        if (!($servico instanceof Servico)) {
            $servico = $this->storage
                ->getRepository(Servico::class)
                ->find($servico);
        }
        
        if ($usuario !== null && !($usuario instanceof Usuario)) {
            $usuario = $this->storage
                ->getRepository(Usuario::class)
                ->find($usuario);
        }
        
        $this->dispatcher->createAndDispatch('attending.pre-redirect', [$atendimento, $unidade, $servico, $usuario], true);
        
        $atendimento->setStatus(self::ERRO_TRIAGEM);
        $atendimento->setDataFim(new DateTime());
        
        $tempoPermanencia = $atendimento->getDataFim()->diff($atendimento->getDataChegada());
        $tempoAtendimento = new \DateInterval('P0M');
        
        $atendimento->setTempoPermanencia($tempoPermanencia);
        $atendimento->setTempoAtendimento($tempoAtendimento);
        
        $novo = $this->copyToRedirect($atendimento, $unidade, $servico, $usuario);
        
        $om = $this->storage->getManager();
        $om->merge($atendimento);
        $om->persist($novo);
        
        $om->flush();

        $this->dispatcher->createAndDispatch('attending.redirect', [$atendimento, $novo], true);

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
        $this->dispatcher->createAndDispatch('attending.pre-transfer', [ $atendimento, $unidade, $novoServico, $novaPrioridade ], true);

        // transfere apenas se a data fim for nula (nao finalizados)
        $success = $this->storage
            ->getManager()
            ->createQueryBuilder()
            ->update(Atendimento::class, 'e')
            ->set('e.servico', ':servico')
            ->set('e.prioridade', ':prioridade')
            ->where('e.id = :id')
            ->andWhere('e.unidade = :unidade')
            ->andWhere('e.dataFim IS NULL')
            ->setParameters([
                'servico'    => $novoServico,
                'prioridade' => $novaPrioridade,
                'id'         => $atendimento,
                'unidade'    => $unidade
            ])
            ->getQuery()
            ->execute() > 0;

        if ($success) {
            $this->storage->getManager()->refresh($atendimento);
            $this->dispatcher->createAndDispatch('attending.transfer', [$atendimento], true);
        }

        return $success;
    }

    /**
     * Atualiza o status da senha para cancelado.
     *
     * @param Atendimento $atendimento
     * @param Unidade     $unidade
     */
    public function cancelar(Atendimento $atendimento)
    {
        if ($atendimento->getDataFim() !== null) {
            throw new Exception('Erro ao tentar cancelar um serviço já encerrado.');
        }
        
        $this->dispatcher->createAndDispatch('attending.pre-cancel', $atendimento, true);
        
        $now = new DateTime();
        $atendimento->setDataFim($now);
        
        if ($atendimento->getDataChegada()) {
            $tempoPermanencia = $atendimento->getDataFim()->diff($atendimento->getDataChegada());
        } else {
            $tempoPermanencia = $atendimento->getDataFim()->diff($now);
        }
        
        if ($atendimento->getDataInicio()) {
            $tempoAtendimento = $atendimento->getDataFim()->diff($atendimento->getDataInicio());
        } else {
            $tempoAtendimento = null;
        }
        
        $atendimento->setTempoPermanencia($tempoPermanencia);
        $atendimento->setTempoAtendimento($tempoAtendimento);
        $atendimento->setStatus(self::SENHA_CANCELADA);
        
        $em = $this->storage->getManager();
        $em->merge($atendimento);
        $em->flush();

        $this->dispatcher->createAndDispatch('attending.cancel', $atendimento, true);
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
        $this->dispatcher->createAndDispatch('attending.pre-reactivate', $atendimento, true);

        // reativa apenas se estiver finalizada (data fim diferente de nulo)
        $success = $this->storage
            ->getManager()
            ->createQueryBuilder()
            ->update(Atendimento::class, 'e')
            ->set('e.status', ':status')
            ->set('e.dataFim', ':data')
            ->where('e.id = :id')
            ->andWhere('e.id = :id')
            ->andWhere('e.unidade = :unidade')
            ->andWhere('e.status IN (:statuses)')
            ->setParameters([
                'status'   => self::SENHA_EMITIDA,
                'statuses' => [self::SENHA_CANCELADA, self::NAO_COMPARECEU],
                'id'       => $atendimento,
                'unidade'  => $unidade
            ])
            ->getQuery()
            ->execute() > 0;

        if ($success) {
            $this->storage->getManager()->refresh($atendimento);
            $this->dispatcher->createAndDispatch('attending.reactivate', $atendimento, true);
        }

        return $success;
    }
    
    /**
     * 
     * @param Atendimento $atendimento
     * @param Unidade     $unidade
     * @param Servico[]   $servicosRealizados
     * @param Servico     $servicoRedirecionado
     * @param Usuario     $novoUsuario
     * @throws Exception
     */
    public function encerrar(
        Atendimento $atendimento,
        Unidade $unidade,
        array $servicosRealizados,
        Servico $servicoRedirecionado = null,
        Usuario $novoUsuario = null
    ) {
        if ($atendimento->getStatus() !== AtendimentoService::ATENDIMENTO_INICIADO) {
            throw new Exception(
                sprintf(
                    'Erro ao tentar encerrar um atendimento nao iniciado (%s)',
                    $atendimento->getId()
                )
            );
        }
        
        $executados = [];
        $servicoRepository  = $this->storage->getRepository(Servico::class);
        
        foreach ($servicosRealizados as $s) {
            if ($s instanceof Servico) {
                $servico = $s;
            } else {
                $servico = $servicoRepository->find($s);
            }

            if (!$servico) {
                $error = $this->translator->trans('error.invalid_service');
                throw new Exception($error);
            }
            
            $executado = new AtendimentoCodificado();
            $executado->setAtendimento($atendimento);
            $executado->setServico($servico);
            $executado->setPeso(1);
            $executados[] = $executado;
        }
        
        $novoAtendimento = null;
        
        // verifica se esta encerrando e redirecionando
        if ($servicoRedirecionado) {
            $novoAtendimento = $this->copyToRedirect($atendimento, $unidade, $servicoRedirecionado, $novoUsuario);
        }
        
        $atendimento->setDataFim(new DateTime);
        $atendimento->setStatus(AtendimentoService::ATENDIMENTO_ENCERRADO);
        
        $tempoPermanencia = $atendimento->getDataFim()->diff($atendimento->getDataChegada());
        $tempoAtendimento = $atendimento->getDataFim()->diff($atendimento->getDataInicio());
        
        $atendimento->setTempoPermanencia($tempoPermanencia);
        $atendimento->setTempoAtendimento($tempoAtendimento);
        
        $this->storage->encerrar($atendimento, $executados, $novoAtendimento);
    }
    
    public function alteraStatusAtendimentoUsuario(Usuario $usuario, $novoStatus)
    {
        $atual = $this->atendimentoAndamento($usuario->getId());
            
        if (!$atual) {
            $error = $this->translator->trans('error.no_servicing_available');
            throw new Exception($error);
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

        $data = (new DateTime())->format('Y-m-d H:i:s');

        $qb = $this->storage
            ->getManager()
            ->createQueryBuilder()
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
            $error = $this->translator->trans('error.change_status');
            throw new Exception($error);
        }

        $atual->setStatus($novoStatus);

        return $atual;
    }
    
    public function checkServicoUnidade(Unidade $unidade, Servico $servico): ServicoUnidade
    {
        // verificando se o servico esta disponivel na unidade
        $su = $this->storage
            ->getRepository(ServicoUnidade::class)
            ->get($unidade, $servico);
        
        if (!$su) {
            $error = $this->translator->trans('error.service_unity_invalid');
            throw new Exception($error);
        }
        
        if (!$su->isAtivo()) {
            $error = $this->translator->trans('error.service_unity_inactive');
            throw new Exception($error);
        }
        
        return $su;
    }
    
    /**
     * 
     * @param Cliente $cliente
     * @return Cliente
     */
    public function getClienteValido(Cliente $cliente)
    {
        // verificando se o cliente ja existe
        if ($cliente) {
            $clienteExistente = null;
            $clienteRepository = $this->storage->getRepository(Cliente::class);
            
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
            
            // evita gerar cliente sem nome e/ou documento
            if (!$cliente->getDocumento() || !$cliente->getNome()) {
                $cliente = null;
            }
        }
        
        return $cliente;
    }
    
    /**
     * Apaga os dados de atendimento da unidade ou global
     * @param Unidade $unidade
     */
    public function limparDados(Unidade $unidade = null)
    {
        $this->storage->apagarDadosAtendimento($unidade);
    }
    
    /**
     * 
     * @param Atendimento $atendimento
     * @param Unidade     $unidade
     * @param Servico     $servico
     * @param Usuario     $usuario Define o novo atendente (opcional)
     * @return Atendimento
     */
    private function copyToRedirect(Atendimento $atendimento, Unidade $unidade, Servico $servico, Usuario $usuario = null): Atendimento
    {
        // copiando a senha do atendimento atual
        $novo = new Atendimento();
        $novo->setLocal(null);
        $novo->setServico($servico);
        $novo->setUnidade($unidade);
        $novo->setPai($atendimento);
        $novo->setDataChegada(new DateTime());
        $novo->setStatus(self::SENHA_EMITIDA);
        $novo->getSenha()->setSigla($atendimento->getSenha()->getSigla());
        $novo->getSenha()->setNumero($atendimento->getSenha()->getNumero());
        $novo->setUsuario($usuario);
        $novo->setUsuarioTriagem($atendimento->getUsuario());
        $novo->setPrioridade($atendimento->getPrioridade());
        
        if ($atendimento->getCliente()) {
            $novo->setCliente($atendimento->getCliente());
        }
        
        return $novo;
    }
}
