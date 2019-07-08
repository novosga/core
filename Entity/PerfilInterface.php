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
 * Classe Perfil
 * O perfil define permissões de acesso a módulos do sistema.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface PerfilInterface
{
    public function getId();

    /**
     * Retorna o nome do perfil.
     *
     * @return string
     */
    public function getNome(): string;

    /**
     * Retorna a descrição do perfil.
     *
     * @return int
     */
    public function getDescricao(): ?string;

    public function getModulos(): array;
    
    public function getCreatedAt(): DateTimeInterface;

    public function getUpdatedAt(): ?DateTimeInterface;
}
