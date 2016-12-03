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
class UsuarioMeta extends Metadata
{
    /**
     * @var Usuario
     */
    private $usuario;

    public function getEntity()
    {
        return $this->getUsuario();
    }

    public function setEntity($entity)
    {
        $this->setUsuario($entity);
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }
}
