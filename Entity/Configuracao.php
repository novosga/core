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
 * Configuracao
 * Aplication level configuration
 */
class Configuracao implements \JsonSerializable
{
    const STRING  = 1;
    const NUMERIC = 2;
    const COMPLEX = 3;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $type;

    // transient
    private $parsedValue;

    public function __construct(string $namespace = '', string $name = '', $value = null)
    {
        $this->setName($name);
        $this->setValue($value);
    }
    
    public function getNamespace()
    {
        return $this->namespace;
    }
    
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }
        
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getValue()
    {
        if (!$this->parsedValue) {
            $this->parsedValue = ($this->type === self::COMPLEX) ? unserialize($this->value) : $this->value;
        }

        return $this->parsedValue;
    }

    public function setValue($value)
    {
        $this->parsedValue = $value;
        $this->type        = $this->guessType($value);
        $this->value       = ($this->type === self::COMPLEX) ? serialize($value) : $value;
        return $this;
    }

    public function __toString()
    {
        return "{$this->getNamespace()}.{$this->getName()}";
    }

    private function guessType($value): int
    {
        if (is_numeric($value)) {
            return self::NUMERIC;
        } elseif (is_string($value)) {
            return self::STRING;
        } else {
            return self::COMPLEX;
        }
    }

    public function jsonSerialize()
    {
        return [
            'namespace' => $this->getNamespace(),
            'name'      => $this->getName(),
            'value'     => $this->getValue(),
        ];
    }
}
