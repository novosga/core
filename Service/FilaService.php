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
use Novosga\Config\AppConfig;
use Novosga\Entity\Servico;
use Novosga\Entity\Unidade;
use Novosga\Entity\Usuario;
use Novosga\Entity\Atendimento;
use Novosga\Entity\ServicoUsuario;

/**
 * FilaService
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class FilaService extends ModelService
{

    /**
     * Retorna a fila de atendimentos do usuario.
     *
     * @param Unidade          $unidade
     * @param ServicoUsuario[] $servicosUsuario
     * @param int              $tipoAtendimento
     * @param int              $maxResults
     *
     * @return array
     */
    public function filaAtendimento(Unidade $unidade, $servicosUsuario, $tipoAtendimento = 1, $maxResults = 0)
    {
        $usuario = null;
        
        $ids = [0];
        foreach ($servicosUsuario as $servico) {
            $usuario = $servico->getUsuario();
            $ids[] = $servico->getServico()->getId();
        }
        
        $where = '';
        // se nao atende todos, filtra pelo tipo de atendimento
        if ($tipoAtendimento !== 1) {
            $s = ($tipoAtendimento === 2) ? '=' : '>';
            $where = "prioridade.peso $s 0";
        }
        
        $builder = $this->builder($usuario)
                        ->andWhere('atendimento.status = :status')
                        ->andWhere('servicoUnidade.unidade = :unidade')
                        ->andWhere('servico.id IN (:servicos)');
        
        $params = [
            'status' => AtendimentoService::SENHA_EMITIDA,
            'unidade' => $unidade,
            'servicos' => $ids
        ];
        
        if ($usuario) {
            $builder->join(ServicoUsuario::class, 'servicoUsuario', 'WITH', 'servicoUsuario.servico = servico AND servicoUsuario.usuario = :usuario');
            $params['usuario'] = $usuario;
        }
        
        if (!empty($where)) {
            $builder->andWhere($where);
        }

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
     * @param Usuario $usuario
     *
     * @return array
     */
    public function filaServico(Unidade $unidade, Servico $servico)
    {
        $builder = $this->builder();
        
        $params = [
            'status' => AtendimentoService::SENHA_EMITIDA,
            'unidade' => $unidade,
            'servico' => $servico
        ];
        
        $builder
                ->where('atendimento.status = :status')
                ->andWhere('servicoUnidade.unidade = :unidade')
                ->andWhere('servicoUnidade.servico = :servico');
        
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
    public function builder(Usuario $usuario = null)
    {
        $qb = $this->em
            ->createQueryBuilder()
            ->select([
                'atendimento',
                'prioridade',
                'servicoUnidade', 
                'servico'
            ])
            ->from(Atendimento::class, 'atendimento')
            ->join('atendimento.prioridade', 'prioridade')
            ->join('atendimento.servicoUnidade', 'servicoUnidade')
            ->join('servicoUnidade.servico', 'servico');
        
        return $qb;
    }

    /**
     * Aplica a ordenação na QueryBuilder.
     *
     * @param QueryBuilder $builder
     */
    public function applyOrders(QueryBuilder $builder, Unidade $unidade, Usuario $usuario = null)
    {
        $ordering = AppConfig::getInstance()->get('queue.ordering');
        if (is_callable($ordering)) {
            $ordering = $ordering($unidade, $usuario);
        }
        if (!$ordering || empty($ordering)) {
            $ordering = [
                // priority
                [
                    'exp'   => 'prioridade.peso',
                    'order' => 'DESC',
                ]
            ];
            if ($usuario) {
                // peso servico x usuario
                $ordering[] = [
                    'exp'   => 'servicoUsuario.peso',
                    'order' => 'ASC',
                ];
            }
            
            // ticket number
            $ordering[] = [
                'exp'   => 'atendimento.senha.numero',
                'order' => 'ASC',
            ];
        }
        foreach ($ordering as $item) {
            if (!isset($item['exp'])) {
                break;
            }
            $exp = $item['exp'];
            $order = isset($item['order']) ? $item['order'] : 'ASC';
            $builder->addOrderBy($exp, $order);
        }
    }
}
