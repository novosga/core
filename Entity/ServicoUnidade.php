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
     * O atendimento do serviço pode ser tanto normal quanto prioridade
     */
    const ATENDIMENTO_TODOS      = 1;

    /**
     * O atendimento do serviço só poder ser normal
     */
    const ATENDIMENTO_NORMAL     = 2;

    /**
     * O atendimento do serviço só poder ser prioridade
     */
    const ATENDIMENTO_PRIORIDADE = 3;

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
    private $tipo;

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
     * @var int
     */
    private $maximo;

    /**
     * @var string
     */
    private $mensagem;

    public function __construct()
    {
        $this->tipo          = self::ATENDIMENTO_TODOS;
        $this->numeroInicial = 1;
        $this->incremento    = 1;
        $this->peso          = 1;
        $this->sigla         = '';
        $this->mensagem      = '';
    }

    /**
     * @return Servico
     */
    public function getServico(): ?Servico
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
    public function getUnidade(): ?Unidade
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

    public function getDepartamento(): ?Departamento
    {
        return $this->departamento;
    }

    /**
     * @return Local
     */
    public function getLocal(): ?Local
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

    public function getPeso(): int
    {
        return $this->peso;
    }

    public function setPeso(int $peso): self
    {
        $this->peso = $peso;

        return $this;
    }

    public function setSigla(string $sigla): self
    {
        $this->sigla = $sigla;

        return $this;
    }

    public function getSigla(): string
    {
        return $this->sigla;
    }

    public function getTipo(): int
    {
        return $this->tipo;
    }

    public function getIncremento(): int
    {
        return $this->incremento;
    }

    public function getNumeroInicial(): int
    {
        return $this->numeroInicial;
    }

    public function getNumeroFinal(): ?int
    {
        return $this->numeroFinal;
    }

    public function getMaximo(): ?int
    {
        return $this->maximo;
    }

    public function setTipo(int $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function setIncremento(int $incremento): self
    {
        $this->incremento = $incremento;

        return $this;
    }

    public function setNumeroInicial(int $numeroInicial): self
    {
        $this->numeroInicial = $numeroInicial;

        return $this;
    }

    public function setNumeroFinal(?int $numeroFinal): self
    {
        $this->numeroFinal = $numeroFinal;

        return $this;
    }

    public function setMaximo(?int $maximo): self
    {
        $this->maximo = $maximo;

        return $this;
    }

    public function getMensagem(): ?string
    {
        return $this->mensagem;
    }

    public function setMensagem(string $mensagem): self
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
            'tipo'          => $this->getTipo(),
            'mensagem'      => $this->getMensagem(),
            'numeroInicial' => $this->getNumeroInicial(),
            'numeroFinal'   => $this->getNumeroFinal(),
            'incremento'    => $this->getIncremento(),
            'maximo'        => $this->getMaximo(),
        ];
    }
}
