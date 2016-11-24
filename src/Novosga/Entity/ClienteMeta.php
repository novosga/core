<?php

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
