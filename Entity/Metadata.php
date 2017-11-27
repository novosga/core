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
 * Abstract metadata.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
abstract class Metadata implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;
    
    /**
     * @var mixed
     */
    protected $entity;
    
    public function setEntity($entity)
    {
        $this->entity = $entity;
        
        return $this;
    }
    
    public function getEntity()
    {
        return $this->entity;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'name'  => $this->getName(),
            'value' => $this->getValue(),
        ];
    }
}
