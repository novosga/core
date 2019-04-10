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
 * ClienteMeta
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class ClienteMeta extends EntityMetadata
{
    public function getCliente()
    {
        return $this->getEntity();
    }

    public function setCliente(Cliente $cliente): self
    {
        return $this->setEntity($cliente);
    }
}
