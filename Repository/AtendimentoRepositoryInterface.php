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

use Doctrine\Common\Persistence\ObjectRepository;
use Novosga\Entity\AtendimentoInterface;
use Novosga\Entity\ServicoInterface;
use Novosga\Entity\UnidadeInterface;

/**
 * AtendimentoRepositoryInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface AtendimentoRepositoryInterface extends ObjectRepository
{
    /**
     * Retorna o par do id do serviço e o total de atendimentos
     * @param UnidadeInterface          $unidade
     * @param ServicoInterface[]|int[]  $servicos
     * @param string                    $status
     * @return array
     */
    public function countByServicos(UnidadeInterface $unidade, array $servicos, $status = null);
    
    /**
     * 
     * @param UnidadeInterface $unidade
     * @param ServicoInterface $servico
     * @return AtendimentoInterface
     */
    public function getUltimo(UnidadeInterface $unidade, ServicoInterface $servico = null);
}
