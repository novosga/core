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
use Doctrine\Common\Collections\Collection;

/**
 * Servico
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface ServicoInterface
{   
    public function getId();

    public function getNome(): string;

    public function getDescricao(): ?string;

    public function getMestre(): ?ServicoInterface;

    public function isMestre(): bool;

    public function isAtivo(): bool;

    public function getPeso(): int;

    public function getSubServicos(): Collection;
    
    public function getCreatedAt(): DateTimeInterface;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function getDeletedAt(): ?DateTimeInterface;
}
