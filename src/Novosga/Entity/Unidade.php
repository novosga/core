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
 * Unidade de atendimento.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class Unidade implements \JsonSerializable
{
    /**
     * @var mixed
     */
    protected $id;
    
    /**
     * @var string
     */
    private $codigo;

    /**
     * @var string
     */
    private $nome;

    /**
     * @var bool
     */
    private $status;

    /**
     * @var ConfiguracaoImpressao
     */
    private $impressao;

    public function __construct()
    {
        $this->impressao = new ConfiguracaoImpressao($this);
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

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getImpressao()
    {
        return $this->impressao;
    }
    
    public function __toString()
    {
        return $this->getNome();
    }

    public function jsonSerialize()
    {
        return [
            'id'        => $this->getId(),
            'codigo'    => $this->getCodigo(),
            'nome'      => $this->getNome(),
            'status'    => $this->getStatus() == true,
            'impressao' => $this->getImpressao(),
        ];
    }
}
