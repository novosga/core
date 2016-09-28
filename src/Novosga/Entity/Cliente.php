<?php

namespace Novosga\Entity;

/**
 * Classe auxiliar.
 *
 * @author rogerio
 */
class Cliente
{
    /**
     * @var string
     */
    private $nome;
    
    /**
     * @var string
     */
    private $documento;

    public function __construct()
    {
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setDocumento($documento)
    {
        $this->documento = $documento;
    }

    public function getDocumento()
    {
        return $this->documento;
    }

    public function __toString()
    {
        return $this->getNome();
    }
    
    public function jsonSerialize()
    {
        return [
            'nome'      => $this->getNome(),
            'documento' => $this->getDocumento(),
        ];
    }
}
