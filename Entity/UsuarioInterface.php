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
 * Usuario
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface UsuarioInterface
{
    public function getId();

    public function getLogin(): string;

    public function getNome(): string;

    public function getSobrenome(): ?string;

    /**
     * Retorna o nome completo do usuario (nome + sobrenome).
     *
     * @return string
     */
    public function getNomeCompleto(): string;

    public function getEmail(): ?string;

    public function getSenha(): ?string;

    public function getLotacao(): ?LotacaoInterface;

    public function getLotacoes(): Collection;

    public function isAtivo(): bool;

    public function getUltimoAcesso(): ?DateTimeInterface;

    public function getIp(): ?string;

    public function getSessionId(): ?string;

    public function isAdmin(): bool;

    public function getCreatedAt(): DateTimeInterface;

    public function getUpdatedAt(): ?DateTimeInterface;

    public function getDeletedAt(): ?DateTimeInterface;
}
