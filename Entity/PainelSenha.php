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
  * Senha enviada ao painel
  *
  * @author Rogerio Lino <rogeriolino@gmail.com>
  */
class PainelSenha implements \JsonSerializable
{
    /**
     * @var mixed
     */
    protected $id;
    
    /**
     * @var Servico
     */
    private $servico;

    /**
     * @var Unidade
     */
    private $unidade;

    /**
     * @var int
     */
    private $numeroSenha;

    /**
     * @var string
     */
    private $siglaSenha;

    /**
     * @var string
     */
    private $mensagem;

    /**
     * @var string
     */
    private $local;

    /**
     * @var int
     */
    private $numeroLocal;

    /**
     * @var int
     */
    private $peso;

    /**
     * @var string
     */
    private $prioridade;

    /**
     * @var string
     */
    private $nomeCliente;

    /**
     * @var string
     */
    private $documentoCliente;

    public function getServico()
    {
        return $this->servico;
    }

    public function setServico($servico): self
    {
        $this->servico = $servico;

        return $this;
    }

    public function getUnidade()
    {
        return $this->unidade;
    }

    public function setUnidade($unidade): self
    {
        $this->unidade = $unidade;

        return $this;
    }

    public function getNumeroSenha()
    {
        return $this->numeroSenha;
    }

    public function setNumeroSenha($numeroSenha): self
    {
        $this->numeroSenha = $numeroSenha;

        return $this;
    }

    public function getSiglaSenha()
    {
        return $this->siglaSenha;
    }

    public function setSiglaSenha($siglaSenha): self
    {
        $this->siglaSenha = $siglaSenha;

        return $this;
    }

    public function getMensagem()
    {
        return $this->mensagem;
    }

    public function setMensagem($mensagem): self
    {
        $this->mensagem = $mensagem;

        return $this;
    }

    public function getLocal()
    {
        return $this->local;
    }

    public function setLocal($local): self
    {
        $this->local = $local;

        return $this;
    }

    public function getNumeroLocal()
    {
        return $this->numeroLocal;
    }

    public function setNumeroLocal($numeroLocal): self
    {
        $this->numeroLocal = $numeroLocal;

        return $this;
    }

    public function getPeso()
    {
        return $this->peso;
    }

    public function setPeso($peso): self
    {
        $this->peso = $peso;

        return $this;
    }

    public function getPrioridade()
    {
        return $this->prioridade;
    }

    public function getNomeCliente()
    {
        return $this->nomeCliente;
    }

    public function getDocumentoCliente()
    {
        return $this->documentoCliente;
    }

    public function setPrioridade($prioridade): self
    {
        $this->prioridade = $prioridade;

        return $this;
    }

    public function setNomeCliente($nomeCliente): self
    {
        $this->nomeCliente = $nomeCliente;

        return $this;
    }

    public function setDocumentoCliente($documentoCliente): self
    {
        $this->documentoCliente = $documentoCliente;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
           'id'               => $this->getId(),
           'senha'            => $this->getSiglaSenha().str_pad($this->getNumeroSenha(), 3, '0', STR_PAD_LEFT),
           'local'            => $this->getLocal(),
           'numeroLocal'      => $this->getNumeroLocal(),
           'peso'             => $this->getPeso(),
           'prioridade'       => $this->getPrioridade(),
           'nomeCliente'      => $this->getNomeCliente(),
           'documentoCliente' => $this->getDocumentoCliente(),
        ];
    }
}
