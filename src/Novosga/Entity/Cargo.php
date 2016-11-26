<?php

namespace Novosga\Entity;

/**
 * Classe Cargo
 * Um cargo define permissões de acesso a módulos do sistema.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class Cargo implements \JsonSerializable
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
    private $descricao;

    /**
     * @var Modulo[]
     */
    private $modulos;
    
    public function __construct()
    {
        $this->modulos = [];
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * Define o nome do Cargo.
     *
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * Retorna a descrição do Cargo.
     *
     * @return int
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Define a descrição do Cargo.
     *
     * @param string $nome
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * Retorna o nome do Cargo.
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    public function getModulos()
    {
        return $this->modulos;
    }

    public function setModulos($modulos)
    {
        $this->modulos = $modulos;
        return $this;
    }
    
    public function __toString()
    {
        return $this->nome;
    }

    public function jsonSerialize()
    {
        return [
            'id'        => $this->getId(),
            'nome'      => $this->getNome(),
            'descricao' => $this->getDescricao(),
            'modulos'   => $this->getModulos(),
        ];
    }
}
