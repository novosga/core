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

use Doctrine\ORM\QueryBuilder;
use Novosga\Entity\Atendimento;
use Novosga\Entity\Servico;
use Novosga\Entity\ServicoUnidade;
use Novosga\Entity\ServicoUsuario;
use Novosga\Entity\Unidade;
use Novosga\Entity\Usuario;
use Novosga\Infrastructure\StorageInterface;

/**
 * FilaService
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class FilaService extends StorageAwareService
{
    const TIPO_TODOS       = 'todos';
    const TIPO_NORMAL      = 'normal';
    const TIPO_PRIORIDADE  = 'prioridade';
    const TIPO_AGENDAMENTO = 'agendamento';
    
    /**
     * @var Configuration
     */
    private $config;
    
    public function __construct(StorageInterface $storage, Configuration $config)
    {
        parent::__construct($storage);
        $this->config = $config;
    }

    /**
     * Retorna a fila de atendimentos do usuario.
     *
     * @param Unidade          $unidade
     * @param Usuario          $usuario
     * @param ServicoUsuario[] $servicosUsuario
     * @param string           $tipoFila
     * @param int              $maxResults
     *
     * @return array
     */
    public function filaAtendimento(
        Unidade $unidade,
        Usuario $usuario,
        array $servicosUsuario,
        $tipoFila = self::TIPO_TODOS,
        $maxResults = 0
    ) {
        $ids = [];
        foreach ($servicosUsuario as $servico) {
            if ($servico->getUsuario()->getId() === $usuario->getId()) {
                $ids[]   = $servico->getServico()->getId();
            }
        }
        
        if (empty($ids)) {
            return [];
        }
        
        $builder = $this
            ->builder()
            ->join(
                ServicoUsuario::class,
                'servicoUsuario',
                'WITH',
                'servicoUsuario.servico = servico AND servicoUsuario.usuario = :usuario'
            )
            ->andWhere('(atendimento.usuario IS NULL OR atendimento.usuario = :usuario)')
            ->andWhere('atendimento.status = :status')
            ->andWhere('atendimento.unidade = :unidade')
            ->andWhere('servico.id IN (:servicos)');
        
        // se nao atende todos, filtra pelo tipo de atendimento
        switch ($tipoFila) {
            case self::TIPO_NORMAL:
            case self::TIPO_PRIORIDADE:
                $s = ($tipoFila === self::TIPO_NORMAL) ? '=' : '>';
                $where = "prioridade.peso $s 0";
                $builder->andWhere($where);
                break;
            case self::TIPO_AGENDAMENTO:
                $builder->andWhere("atendimento.dataAgendamento IS NOT NULL");
                break;
        }
        
        $params = [
            'status'   => AtendimentoService::SENHA_EMITIDA,
            'unidade'  => $unidade,
            'usuario'  => $usuario,
            'servicos' => $ids,
        ];
        
        $this->applyOrders($builder, $unidade, $usuario);

        $query = $builder
            ->setParameters($params)
            ->getQuery();

        if ($maxResults > 0) {
            $query->setMaxResults($maxResults);
        }

        return $query->getResult();
    }

    /**
     * Retorna a fila de espera do serviço na unidade.
     *
     * @param Unidade $unidade
     * @param Servico $servico
     *
     * @return array
     */
    public function filaServico(Unidade $unidade, Servico $servico)
    {
        $builder = $this->builder();
        
        $params = [
            'status'  => AtendimentoService::SENHA_EMITIDA,
            'unidade' => $unidade,
            'servico' => $servico,
        ];
        
        $builder
            ->where('atendimento.status = :status')
            ->andWhere('atendimento.unidade = :unidade')
            ->andWhere('atendimento.servico = :servico');
        
        $this->applyOrders($builder, $unidade);

        $rs = $builder
            ->setParameters($params)
            ->getQuery()
            ->getResult();

        return $rs;
    }

    /**
     * Retorna a fila de espera do serviço na unidade.
     *
     * @param Unidade $unidade
     *
     * @return array
     */
    public function filaUnidade(Unidade $unidade)
    {
        $builder = $this->builder();
        
        $params = [
            'status'  => AtendimentoService::SENHA_EMITIDA,
            'unidade' => $unidade,
        ];
        
        $builder
            ->where('atendimento.status = :status')
            ->andWhere('atendimento.unidade = :unidade');
        
        $this->applyOrders($builder, $unidade);

        $rs = $builder
            ->setParameters($params)
            ->getQuery()
            ->getResult();

        return $rs;
    }

    /**
     * @return QueryBuilder
     */
    private function builder()
    {
        $qb = $this
            ->storage
            ->getManager()
            ->createQueryBuilder()
            ->select([
                'atendimento',
                'prioridade',
                'unidade',
                'servico',
            ])
            ->from(Atendimento::class, 'atendimento')
            ->join('atendimento.prioridade', 'prioridade')
            ->join('atendimento.unidade', 'unidade')
            ->join('atendimento.servico', 'servico')
            ->join(
                ServicoUnidade::class,
                'servicoUnidade',
                'WITH',
                'servicoUnidade.unidade = unidade AND servicoUnidade.servico = servico'
            );
        
        return $qb;
    }

    /**
     * Aplica a ordenação na QueryBuilder.
     *
     * @param QueryBuilder $builder
     */
    private function applyOrders(QueryBuilder $builder, Unidade $unidade, Usuario $usuario = null)
    {
        $ordering = $this->config->get('queue.ordering');
        
        if (is_callable($ordering)) {
            $param = new \Novosga\Configuration\OrderingParameter();
            $param->setUnidade($unidade);
            $param->setUsuario($usuario);
            $param->setQueryBuilder($builder);
            $param->setStorage($this->storage);
            
            $ordering = $ordering($param);
        }
        
        if (is_array($ordering)) {
            foreach ($ordering as $item) {
                if (!isset($item['exp'])) {
                    break;
                }
                $exp   = $item['exp'];
                $order = isset($item['order']) ? $item['order'] : 'ASC';
                $builder->addOrderBy($exp, $order);
            }
        }
    }
}
