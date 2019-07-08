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

use DateTimeInterface;

/**
 * AgendamentoInterface
 *
 * @author rogerio
 */
interface AgendamentoInterface
{
    public function getId();

    public function getData(): ?DateTimeInterface;

    public function getHora(): ?DateTimeInterface;

    public function getCliente(): ?ClienteInterface;

    public function getUnidade(): ?UnidadeInterface;

    public function getServico(): ?ServicoInterface;
    
    public function getDataConfirmacao(): ?DateTimeInterface;
}