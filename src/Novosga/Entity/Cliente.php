<?php

namespace Novosga\Entity;

/**
 * Classe auxiliar.
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

    public function setDocumento($documento)
    {
        $this->documento = $documento;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setTelefone($telefone)
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
            'nome'      => $this->getNome(),
            'documento' => $this->getDocumento(),
            'email'     => $this->getEmail(),
            'telefone'  => $this->getTelefone(),
        ];
    }
}
