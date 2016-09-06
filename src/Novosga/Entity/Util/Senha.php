<?php

namespace Novosga\Entity\Util;

use Novosga\Entity\Model;
use Novosga\Entity\Prioridade;

/**
  * Classe Senha
  * Responsavel pelas informacoes do Senha.
  */
 class Senha extends Model
 {
     const LENGTH = 3;
     const TIPO_NUMERACAO = 'numeracao';
     const NUMERACAO_UNICA = 1;
     const NUMERACAO_SERVICO = 2;

     private $sigla;
     private $numero;
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
     * @param Prioridade $pri
     */
    public function setPrioridade($pri)
    {
        $this->prioridade = $pri;
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
    public function toString()
    {
        return $this->getSigla().$this->getNumeroZeros();
    }

    /**
     * Retorna resultado do método toString.
     *
     * @return string
     */
    public function __tostring()
    {
        return $this->toString();
    }
 }
