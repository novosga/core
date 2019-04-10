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
abstract class AbstractMetadata implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $namespace;
    
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;
    
    public function getNamespace()
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }
        
    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'namespace' => $this->getName(),
            'name'      => $this->getNamespace(),
            'value'     => $this->getValue(),
        ];
    }
}
