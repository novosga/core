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
 * Servico Unidade
 * Configuração do serviço na unidade
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class ServicoUnidade implements \JsonSerializable
{
    /**
     * @var Servico
     */
    private $servico;

    /**
     * @var Unidade
     */
    private $unidade;

    /**
     * @var Departamento
     */
    private $departamento;

    /**
     * @var Local
     */
    private $local;

    /**
     * @var string
     */
    private $sigla;

    /**
     * @var int
     */
    private $ativo;

    /**
     * @var int
     */
    private $peso;

    /**
     * @var bool
     */
    private $prioridade;

    /**
     * @var int
     */
    private $incremento;

    /**
     * @var int
     */
    private $numeroInicial;

    /**
     * @var int
     */
    private $numeroFinal;

    /**
     * @var string
     */
    private $mensagem;

    public function __construct()
    {
        $this->prioridade = true;
        $this->numeroInicial = 1;
        $this->incremento = 1;
    }

    /**
     * @return Servico
     */
    public function getServico()
    {
        return $this->servico;
    }

    public function setServico(Servico $servico)
    {
        $this->servico = $servico;
    }

    /**
     * @return Unidade
     */
    public function getUnidade()
    {
        return $this->unidade;
    }

    public function setUnidade(Unidade $unidade)
    {
        $this->unidade = $unidade;
    }

    public function setDepartamento(Departamento $departamento = null)
    {
        $this->departamento = $departamento;
    }

    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * @return Local
     */
    public function getLocal()
    {
        return $this->local;
    }

    public function setLocal(Local $local)
    {
        $this->local = $local;
    }

    public function setAtivo(bool $ativo)
    {
        $this->ativo = !!$ativo;
    }

    public function isAtivo(): bool
    {
        return $this->ativo;
    }

    public function getPeso()
    {
        return $this->peso;
    }

    public function setPeso($peso)
    {
        $this->peso = $peso;
    }

    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
    }

    public function getSigla()
    {
        return $this->sigla;
    }

    public function getPrioridade()
    {
        return $this->prioridade;
    }

    public function getIncremento()
    {
        return $this->incremento;
    }

    public function getNumeroInicial()
    {
        return $this->numeroInicial;
    }

    public function getNumeroFinal()
    {
        return $this->numeroFinal;
    }

    public function setPrioridade($prioridade)
    {
        $this->prioridade = $prioridade;
        return $this;
    }

    public function setIncremento($incremento)
    {
        $this->incremento = $incremento;
        return $this;
    }

    public function setNumeroInicial($numeroInicial)
    {
        $this->numeroInicial = $numeroInicial;
        return $this;
    }

    public function setNumeroFinal($numeroFinal)
    {
        $this->numeroFinal = $numeroFinal;
        return $this;
    }

    public function getMensagem()
    {
        return $this->mensagem;
    }

    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
        return $this;
    }

    public function __toString()
    {
        return $this->sigla.' - '.$this->getServico()->getNome();
    }

    public function jsonSerialize()
    {
        return [
            'sigla'         => $this->getSigla(),
            'peso'          => $this->getPeso(),
            'local'         => $this->getLocal(),
            'servico'       => $this->getServico(),
            'departamento'  => $this->getDepartamento(),
            'ativo'         => $this->isAtivo(),
            'prioridade'    => $this->getPrioridade(),
            'mensagem'      => $this->getMensagem(),
            'numeroInicial' => $this->getNumeroInicial(),
            'numeroFinal'   => $this->getNumeroFinal(),
            'incremento'    => $this->getIncremento()
        ];
    }
}
