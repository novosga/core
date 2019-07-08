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
 * Unidade de atendimento.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface UnidadeInterface
{   
    public function getId();
    
    public function getNome(): string;

    public function getDescricao(): ?string;

    public function isAtivo(): bool;

    public function getImpressao(): ConfiguracaoImpressaoInterface;
    
    public function getCreatedAt(): DateTimeInterface;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function getDeletedAt(): ?DateTimeInterface;
}
