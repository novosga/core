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
 * Usuario metadata.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface UsuarioMetadataInterface extends MetadataInterface
{
    /**
     * @return UsuarioInterface
     */
    public function getUsuario(): UsuarioInterface;
}
