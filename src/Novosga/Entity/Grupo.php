<?php

namespace Novosga\Entity;

/**
 * Classe Grupo
 * Atraves do grupo e definido o acesso do Usuario.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class Grupo implements \JsonSerializable
{
    /**
     * @var mixed
     */
    private $id;
    
    /**
     * @var string
     */
    private $nome;

    /**
     * @var string
     */
    private $descricao;

    /**
     * @var Unidade
     */
    private $unidade;
    
    /**
     * @var int
     */
    protected $left = 1;

    /**
     * @var int
     */
    protected $right = 2;

    /**
     * @var int
     */
    protected $level;

    /**
     * @var TreeModel
     */
    protected $parent;
    
    public function __construct()
    {
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
    
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    public function getUnidade()
    {
        return $this->unidade;
    }

    public function setUnidade($unidade)
    {
        $this->unidade = $unidade;
        return $this;
    }

    public function __toString()
    {
        return $this->nome;
    }

    public function getLeft()
    {
        return $this->left;
    }

    public function setLeft($left)
    {
        $this->left = $left;
        return $this;
    }

    public function getRight()
    {
        return $this->right;
    }

    public function setRight($right)
    {
        $this->right = $right;
        return $this;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Retorna se o model é a raíz da árvore.
     *
     * @return bool
     */
    public function isRoot()
    {
        return $this->left == 1;
    }

    /**
     * Retorna se o model é uma folha da árvore.
     *
     * @return bool
     */
    public function isLeaf()
    {
        return $this->right == $this->left + 1;
    }

    /**
     * Retorna se o model e filho do informado via parametro.
     *
     * @param Grupo $parent
     * @return bool
     */
    public function isChild(Grupo $parent)
    {
        return $this->left > $parent->getLeft() && $this->right < $parent->getRight();
    }

    public function jsonSerialize()
    {
        return [
            'id'        => $this->getId(),
            'nome'      => $this->getNome(),
            'descricao' => $this->getDescricao(),
            'left'      => $this->getLeft(),
            'right'     => $this->getRight(),
            'level'     => $this->getLevel(),
            'parent'    => $this->getParent() ? $this->getParent()->getId() : null,
        ];
    }
}
