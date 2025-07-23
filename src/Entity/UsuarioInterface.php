<?php

declare(strict_types=1);

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Entity;

use Doctrine\Common\Collections\Collection;
use JsonSerializable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * Usuario
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface UsuarioInterface extends PasswordAuthenticatedUserInterface, JsonSerializable
{
    public function getId(): ?int;
    public function setId(?int $id): static;

    public function setLogin(?string $login): static;
    public function getLogin(): ?string;

    public function setNome(?string $nome): static;
    public function getNome(): ?string;

    public function setSobrenome(?string $sobrenome): static;
    public function getSobrenome(): ?string;

    public function setEmail(?string $email): static;
    public function getEmail(): ?string;

    public function getLotacao(): ?LotacaoInterface;
    public function setLotacao(?LotacaoInterface $lotacao): static;

    /** @return Collection<int,LotacaoInterface> */
    public function getLotacoes(): Collection;

    public function isAdmin(): bool;
    public function setAdmin(bool $admin): static;

    public function isAtivo(): bool;
    public function setAtivo(bool $ativo): static;

    public function getSenha(): ?string;
    public function setSenha(?string $encoded): static;

    public function addRole(string $role): static;
    /** @return string[] */
    public function getRoles(): array;
}
