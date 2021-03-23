<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Repository;

use Doctrine\Persistence\ObjectRepository;
use Novosga\Entity\Atendimento;
use Novosga\Entity\Servico;
use Novosga\Entity\Unidade;

/**
 * AtendimentoRepositoryInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface AtendimentoRepositoryInterface extends ObjectRepository
{
    /**
     * Retorna o par do id do serviço e o total de atendimentos
     * @param Unidade          $unidade
     * @param Servico[]|int[]  $servicos
     * @param string                    $status
     * @return array
     */
    public function countByServicos(Unidade $unidade, array $servicos, $status = null);
    
    /**
     * 
     * @param Unidade $unidade
     * @param Servico $servico
     * @return Atendimento
     */
    public function getUltimo(Unidade $unidade, Servico $servico = null);
}
