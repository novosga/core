<?php

namespace Novosga\Entity;

/**
 * Servico
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class Servico implements \JsonSerializable
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
     * @var int
     */
    private $status;

    /**
     * @var int
     */
    private $peso;

    /**
     * @var Servico
     */
    private $mestre;

    /**
     * @var Servico[]
     */
    private $subServicos;

    /**
     * @var ServicoUnidade[]
     */
    private $servicosUnidade;

    public function __construct()
    {
        $this->subServicos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->servicosUnidade = new \Doctrine\Common\Collections\ArrayCollection();
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
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setDescricao($desc)
    {
        $this->descricao = $desc;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setMestre(Servico $servico = null)
    {
        $this->mestre = $servico;
    }

    public function getMestre()
    {
        return $this->mestre;
    }

    public function isMestre()
    {
        return ($this->getId() && !$this->getMestre());
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getPeso()
    {
        return $this->peso;
    }

    public function setPeso($peso)
    {
        $this->peso = $peso;
    }

    public function getSubServicos()
    {
        return $this->subServicos;
    }

    public function setSubServicos($subServicos)
    {
        $this->subServicos = $subServicos;
    }

    public function getServicosUnidade()
    {
        return $this->servicosUnidade;
    }

    public function setServicosUnidade(array $servicosUnidade)
    {
        $this->servicosUnidade = $servicosUnidade;
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
            'peso'      => $this->getPeso(),
            'status'    => $this->getStatus(),
            'macro'     => $this->getMestre(),
        ];
    }
}
