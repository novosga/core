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
 * ClienteInterface
 *
 * @author rogerio
 */
interface ClienteInterface
{
    public function getId();

    public function getNome(): ?string;

    public function getDocumento(): ?string;

    public function getEmail(): ?string;

    public function getTelefone(): ?string;
}
