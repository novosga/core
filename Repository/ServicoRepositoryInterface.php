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
use Novosga\Entity\Servico;

/**
 * ServicoRepositoryInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface ServicoRepositoryInterface extends ObjectRepository
{
    /**
     * Retorna os subserviços ativos do serviço informado
     * @param Servico $servico
     * @return Servico[]
     */
    public function getSubservicos(Servico $servico);
}
