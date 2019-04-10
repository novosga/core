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

use DateTime;

/**
 * Agendamento.
 *
 * @author rogerio
 */
class Agendamento implements \JsonSerializable
{
    /**
     * @var mixed
     */
    protected $id;
    
    /**
     * @var DateTime
     */
    private $data;
    
    /**
     * @var DateTime
     */
    private $hora;
    
    /**
     * @var Cliente
     */
    private $cliente;
    
    /**
     * @var Unidade
     */
    private $unidade;
    
    /**
     * @var Servico
     */
    private $servico;
    
    /**
     * @var DateTime
     */
    private $dataConfirmacao;

    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getHora()
    {
        return $this->hora;
    }

    public function getCliente()
    {
        return $this->cliente;
    }

    public function getUnidade()
    {
        return $this->unidade;
    }

    public function getServico()
    {
        return $this->servico;
    }
    
    public function getDataConfirmacao()
    {
        return $this->dataConfirmacao;
    }

    public function setData(?DateTime $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setHora(?DateTime $hora): self
    {
        $this->hora = $hora;

        return $this;
    }

    public function setCliente(?Cliente $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function setUnidade(?Unidade $unidade): self
    {
        $this->unidade = $unidade;

        return $this;
    }

    public function setServico(?Servico $servico): self
    {
        $this->servico = $servico;

        return $this;
    }
    
    public function setDataConfirmacao(?DateTime $dataConfirmacao): self
    {
        $this->dataConfirmacao = $dataConfirmacao;

        return $this;
    }
        
    public function __toString()
    {
        return $this->getId();
    }
    
    public function jsonSerialize()
    {
        return [
            'id'               => $this->getId(),
            'cliente'          => $this->getCliente(),
            'servico'          => $this->getServico(),
            'unidade'          => $this->getUnidade(),
            'data'             => $this->getData() ? $this->getData()->format('Y-m-d') : null,
            'hora'             => $this->getHora() ? $this->getHora()->format('H:i') : null,
            'dataConfirmacao'  => $this->getDataConfirmacao(),
        ];
    }
}
