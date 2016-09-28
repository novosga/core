<?php

namespace Novosga\Entity;

/**
 * Classe Senha
 * Responsavel pelas informacoes do Senha.
 * 
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
class Senha implements \JsonSerializable
{
    const LENGTH = 3;

    /**
     * @var string
     */
    private $sigla;

    /**
     * @var int
     */
    private $numero;

    /**
     * @var Prioridade
     */
    private $prioridade;

    public function __construct()
    {
    }

    /**
     * Define a sigla da senha.
     *
     * @param char $sigla
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
    }

    /**
     * Retorna a sigla da senha.
     *
     * @return char $sigla
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * Define o numero da senha.
     *
     * @param int $numero
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * Retorna o numero da senha.
     *
     * @return int $numero
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Retorna o numero da senha preenchendo com zero (esquerda).
     *
     * @return string
     */
    public function getNumeroZeros()
    {
        return str_pad($this->getNumero(), self::LENGTH, '0', STR_PAD_LEFT);
    }

    /**
     * Define a Prioridade da senha.
     *
     * @param Prioridade $prioridade
     */
    public function setPrioridade(Prioridade $prioridade)
    {
        $this->prioridade = $prioridade;
    }

    /**
     * Retorna a Prioridade da Senha.
     *
     * @return Prioridade
     */
    public function getPrioridade()
    {
        return $this->prioridade;
    }

    /**
     * Retorna a legenda da senha.
     *
     * @return string
     */
    public function getLegenda()
    {
        if ($this->getPrioridade()->getPeso() == 0) {
            return _('Convencional');
        } else {
            return _('Prioridade');
        }
    }

    /**
     * Retorna se a senha tem ou nao prioridade.
     *
     * @return bool
     */
    public function isPrioridade()
    {
        return ($this->getPrioridade()->getPeso() > 0) ? true : false;
    }

    /**
     * Retorna a senha formatada para exibicao.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getSigla() . $this->getNumeroZeros();
    }
    
    public function jsonSerialize()
    {
        return [
            'sigla'          => $this->getSigla(),
            'numero'         => $this->getNumero(),
            'prioridade'     => $this->getSenha()->isPrioridade(),
            'nomePrioridade' => $this->getSenha()->getPrioridade()->getNome(),
        ];
    }
 }