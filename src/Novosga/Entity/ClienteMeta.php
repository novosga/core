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
class ClienteMeta extends Metadata
{
    /**
     * @var Cliente
     */
    private $cliente;

    public function getCliente()
    {
        return $this->cliente;
    }

    public function setCliente(Cliente $cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }
    
    public function getEntity()
    {
        return $this->getCliente();
    }

    public function setEntity($entity)
    {
        $this->setCliente($entity);
    }
}
