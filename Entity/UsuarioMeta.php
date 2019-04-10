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
class UsuarioMeta extends EntityMetadata
{
    /**
     * @return Usuario
     */
    public function getUsuario()
    {
        return $this->getEntity();
    }

    public function setUsuario(Usuario $usuario): self
    {
        return $this->setEntity($usuario);
    }
}
