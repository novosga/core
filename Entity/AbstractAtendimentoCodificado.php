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
 * AbstractAtendimentoCodificado
 * atendimento codificado (servico realizado).
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
abstract class AbstractAtendimentoCodificado
{
    /**
     * @var Servico
     */
    protected $servico;

    /**
     * @var int
     */
    protected $peso;

    abstract public function getAtendimento(): AbstractAtendimento;

    abstract public function setAtendimento(AbstractAtendimento $atendimento): self;

    public function getServico()
    {
        return $this->servico;
    }

    public function setServico($servico): self
    {
        $this->servico = $servico;

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
}
