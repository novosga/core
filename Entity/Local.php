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
 * Local de atendimento
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class Local implements \JsonSerializable
{
    /**
     * @var mixed
     */
    protected $id;
    
    /**
     * @var string
     */
    private $nome;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    
    public function __toString()
    {
        return $this->getNome();
    }

    public function jsonSerialize()
    {
        return [
            'id'   => $this->getId(),
            'nome' => $this->getNome(),
        ];
    }
}
