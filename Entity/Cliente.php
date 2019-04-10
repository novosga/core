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
 * Cliente.
 *
 * @author rogerio
 */
class Cliente implements \JsonSerializable
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
     * @var string
     */
    private $documento;
    
    /**
     * @var string
     */
    private $email;
    
    /**
     * @var string
     */
    private $telefone;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getDocumento()
    {
        return $this->documento;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function setNome($nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function setDocumento($documento): self
    {
        $this->documento = $documento;

        return $this;
    }

    public function setEmail($email): self
    {
        $this->email = $email;
        
        return $this;
    }

    public function setTelefone($telefone): self
    {
        $this->telefone = $telefone;

        return $this;
    }
    
    public function __toString()
    {
        return $this->getNome();
    }
    
    public function jsonSerialize()
    {
        return [
            'id'        => $this->getId(),
            'nome'      => $this->getNome(),
            'documento' => $this->getDocumento(),
            'email'     => $this->getEmail(),
            'telefone'  => $this->getTelefone(),
        ];
    }
}
