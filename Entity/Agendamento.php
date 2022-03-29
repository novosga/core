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
    const SITUACAO_AGENDADO = 'agendado';
    const SITUACAO_CONFIRMADO = 'confirmado';
    const SITUACAO_NAO_COMPARECEU = 'nao_compareceu';

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
     * @var string|null
     */
    private $situacao;
    
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
    
    /**
     * @var string
     */
    private $oid;

    public function __construct()
    {
        $this->situacao = self::SITUACAO_AGENDADO;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getData(): ?DateTime
    {
        return $this->data;
    }

    public function getHora(): ?DateTime
    {
        return $this->hora;
    }

    public function getSituacao(): ?string
    {
        return $this->situacao;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function getUnidade(): ?Unidade
    {
        return $this->unidade;
    }

    public function getServico(): ?Servico
    {
        return $this->servico;
    }
    
    public function getDataConfirmacao(): ?DateTime
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

    public function setSituacao(?string $situacao): self
    {
        $this->situacao = $situacao;

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

    public function getOid(): ?string
    {
        return $this->oid;
    }

    public function setOid(?string $oid): self
    {
        $this->oid = $oid;

        return $this;
    }
        
    public function __toString()
    {
        return $this->getId();
    }
    
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'cliente' => $this->getCliente(),
            'servico' => $this->getServico(),
            'unidade' => $this->getUnidade(),
            'data' => $this->getData() ? $this->getData()->format('Y-m-d') : null,
            'hora' => $this->getHora() ? $this->getHora()->format('H:i') : null,
            'dataConfirmacao' => $this->getDataConfirmacao(),
            'oid' => $this->getOid(),
        ];
    }
}
