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

    public function setServico(Servico $servico): self
    {
        $this->servico = $servico;

        return $this;
    }

    /**
     * @return Unidade
     */
    public function getUnidade()
    {
        return $this->unidade;
    }

    public function setUnidade(Unidade $unidade): self
    {
        $this->unidade = $unidade;

        return $this;
    }

    public function setDepartamento(?Departamento $departamento): self
    {
        $this->departamento = $departamento;

        return $this;
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

    public function setLocal(Local $local): self
    {
        $this->local = $local;

        return $this;
    }

    public function setAtivo(bool $ativo): self
    {
        $this->ativo = !!$ativo;

        return $this;
    }

    public function isAtivo(): bool
    {
        return $this->ativo;
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

    public function setSigla($sigla): self
    {
        $this->sigla = $sigla;

        return $this;
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

    public function setPrioridade($prioridade): self
    {
        $this->prioridade = $prioridade;

        return $this;
    }

    public function setIncremento($incremento): self
    {
        $this->incremento = $incremento;

        return $this;
    }

    public function setNumeroInicial($numeroInicial): self
    {
        $this->numeroInicial = $numeroInicial;

        return $this;
    }

    public function setNumeroFinal($numeroFinal): self
    {
        $this->numeroFinal = $numeroFinal;

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
