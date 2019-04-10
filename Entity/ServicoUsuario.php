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
 * Servico Usuario
 * Configuração do serviço que o usuário atende
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class ServicoUsuario implements \JsonSerializable
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
     * @var Usuario
     */
    private $usuario;

    /**
     * @var int
     */
    private $peso;

    public function __construct()
    {
    }
    
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

    /**
     * @return Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario): self
    {
        $this->usuario = $usuario;

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
    
    public function jsonSerialize()
    {
        return [
            'peso'    => $this->getPeso(),
            'servico' => $this->getServico(),
        ];
    }
}
