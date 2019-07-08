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

use Novosga\Entity\UnidadeInterface;
use Novosga\Entity\ServicoInterface;
use Novosga\Entity\ServicoUnidadeInterface;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * ServicoUnidadeRepositoryInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface ServicoUnidadeRepositoryInterface extends ObjectRepository
{
    /**
     * Retorna todos os serviços da unidade
     *
     * @param UnidadeInterface|int $unidade
     * @return ServicoUnidadeInterface[]
     */
    public function getAll($unidade);
    
    /**
     * Retorna o relacionamento entre o serviço e a unidade.
     *
     * @param UnidadeInterface|int $unidade
     * @param ServicoInterface|int $servico
     * @return ServicoUnidadeInterface|null
     */
    public function get($unidade, $servico);
}
