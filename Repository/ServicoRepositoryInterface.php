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
use Novosga\Entity\ServicoInterface;

/**
 * ServicoRepositoryInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface ServicoRepositoryInterface extends ObjectRepository
{
    /**
     * Retorna os subserviços ativos do serviço informado
     * @param ServicoInterface $servico
     * @return ServicoInterface[]
     */
    public function getSubservicos(ServicoInterface $servico);
}
