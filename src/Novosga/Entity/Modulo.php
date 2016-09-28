<?php

namespace Novosga\Entity;

/**
  * Classe Modulo
  * Para controle dos modulos do sistema.
  *
  */
 class Modulo implements \JsonSerializable
 {

    /**
     * @var string
     */
    private $chave;

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
     * Define a chave do Modulo.
     *
     * @param string $chave
     */
    public function setChave($chave)
    {
        $this->chave = $chave;
        return $this;
    }

    /**
     * Retorna a chave do Modulo.
     *
     * @return string
     */
    public function getChave()
    {
        return $this->chave;
    }

    /**
     * Define o nome do Modulo.
     *
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * Retorna o nome do Modulo.
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

     public function setDescricao($descricao)
     {
         $this->descricao = $descricao;
         return $this;
     }

     public function getDescricao()
     {
         return $this->descricao;
     }

     public function getStatus()
     {
         return $this->status;
     }

     public function setStatus($status)
     {
         $this->status = $status;
         return $this;
     }

     public function getRealPath()
     {
         return self::realPath($this->chave);
     }

     public static function realPath($chave)
     {
         return MODULES_PATH.DS.implode(DS, explode('.', $chave));
     }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getChave();
    }

     public function jsonSerialize()
     {
         return [
            'id'        => $this->getId(),
            'nome'      => $this->getNome(),
            'chave'     => $this->getChave(),
            'descricao' => $this->getDescricao(),
            'status'    => $this->getStatus(),
        ];
     }
 }
