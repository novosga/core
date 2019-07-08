<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Entity;

/**
 * Ticket counter.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface ContadorInterface
{
    /**
     * Get the value of Unidade
     *
     * @return UnidadeInterface
     */
    public function getUnidade(): UnidadeInterface;

    /**
     * Get the value of Servico
     *
     * @return ServicoInterface
     */
    public function getServico(): ServicoInterface;

    /**
     * Get the value of Atual
     *
     * @return int
     */
    public function getNumero(): int;
}
