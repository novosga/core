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

use Novosga\Entity\Cliente;
use Novosga\Entity\Senha;

/**
 * AbstractAtendimento.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
abstract class AbstractAtendimento implements \JsonSerializable
{
    /**
     * @var mixed
     */
    protected $id;
    
    /**
     * @var Unidade
     */
    protected $unidade;

    /**
     * @var Servico
     */
    protected $servico;

    /**
     * @var Usuario
     */
    protected $usuario;

    /**
     * @var Usuario
     */
    protected $usuarioTriagem;

    /**
     * @var int
     */
    protected $local;

    /**
     * @var Prioridade
     */
    private $prioridade;

    /**
     * @var \DateTime
     */
    protected $dataAgendamento;

    /**
     * @var \DateTime
     */
    protected $dataChegada;

    /**
     * @var \DateTime
     */
    protected $dataChamada;

    /**
     * @var \DateTime
     */
    private $dataInicio;

    /**
     * @var \DateTime
     */
    private $dataFim;
    
    /**
     * @var int
     */
    private $tempoEspera;
    
    /**
     * @var int
     */
    private $tempoPermanencia;
    
    /**
     * @var int
     */
    private $tempoAtendimento;
    
    /**
     * @var int
     */
    private $tempoDeslocamento;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var Cliente
     */
    protected $cliente;

    /**
     * @var Senha
     */
    protected $senha;

    /**
     * @var Atendimento
     */
    protected $pai;
    
    
    public function __construct()
    {
        $this->senha = new Senha();
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function getUnidade()
    {
        return $this->unidade;
    }

    public function setUnidade($unidade)
    {
        $this->unidade = $unidade;

        return $this;
    }

    public function getServico()
    {
        return $this->servico;
    }

    public function setServico($servico)
    {
        $this->servico = $servico;

        return $this;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function setUsuarioTriagem(Usuario $usuario)
    {
        $this->usuarioTriagem = $usuario;

        return $this;
    }

    public function getUsuarioTriagem()
    {
        return $this->usuarioTriagem;
    }

    public function getLocal()
    {
        return $this->local;
    }

    public function setLocal($local)
    {
        $this->local = $local;

        return $this;
    }
    
    public function getDataAgendamento()
    {
        return $this->dataAgendamento;
    }

    public function setDataAgendamento(\DateTime $dataAgendamento = null)
    {
        $this->dataAgendamento = $dataAgendamento;
        
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataChegada()
    {
        return $this->dataChegada;
    }

    public function setDataChegada(\DateTime $dataChegada = null)
    {
        $this->dataChegada = $dataChegada;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataChamada()
    {
        return $this->dataChamada;
    }

    public function setDataChamada(\DateTime $dataChamada = null)
    {
        $this->dataChamada = $dataChamada;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    public function setDataInicio(\DateTime $dataInicio = null)
    {
        $this->dataInicio = $dataInicio;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDataFim()
    {
        return $this->dataFim;
    }

    public function setDataFim(\DateTime $dataFim = null)
    {
        $this->dataFim = $dataFim;

        return $this;
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
    
    public function setCliente(Cliente $cliente)
    {
        $this->cliente = $cliente;
        return $this;
    }
    
    public function getPai()
    {
        return $this->pai;
    }

    public function setPai($pai)
    {
        $this->pai = $pai;

        return $this;
    }
    
    /**
     * @param \DateInterval $tempoEspera
     * @return $this
     */
    public function setTempoEspera(\DateInterval $tempoEspera)
    {
        $this->tempoEspera = $this->dateIntervalToSeconds($tempoEspera);
        return $this;
    }

    /**
     * Retorna o tempo de espera do cliente até ser atendido.
     * A diferença entre a data de chegada até a data de chamada (ou atual).
     *
     * @return \DateInterval
     */
    public function getTempoEspera()
    {
        if ($this->tempoEspera) {
            return new \DateInterval("PT{$this->tempoEspera}S");
        }
        
        $now = new \DateTime();
        $interval = $now->diff($this->getDataChegada());

        return $interval;
    }
    
    /**
     * @param \DateInterval $tempoPermanencia
     * @return $this
     */
    public function setTempoPermanencia(\DateInterval $tempoPermanencia)
    {
        $this->tempoPermanencia = $this->dateIntervalToSeconds($tempoPermanencia);
        return $this;
    }
    
    /**
     * Retorna o tempo de permanência do cliente na unidade.
     * A diferença entre a data de chegada até a data de fim de atendimento.
     *
     * @return \DateInterval
     */
    public function getTempoPermanencia()
    {
        if ($this->tempoPermanencia) {
            return new \DateInterval("PT{$this->tempoPermanencia}S");
        }
        
        $interval = new \DateInterval('P0M');
        if ($this->getDataFim()) {
            $interval = $this->getDataFim()->diff($this->getDataChegada());
        }

        return $interval;
    }
    
    /**
     * @param \DateInterval $tempoAtendimento
     * @return $this
     */
    public function setTempoAtendimento(\DateInterval $tempoAtendimento)
    {
        $this->tempoAtendimento = $this->dateIntervalToSeconds($tempoAtendimento);
        return $this;
    }

    /**
     * Retorna o tempo total do atendimento.
     * A diferença entre a data de início e fim do atendimento.
     *
     * @return \DateInterval
     */
    public function getTempoAtendimento()
    {
        if ($this->tempoAtendimento) {
            return new \DateInterval("PT{$this->tempoAtendimento}S");
        }
        
        $interval = new \DateInterval('P0M');
        if ($this->getDataFim()) {
            $interval = $this->getDataFim()->diff($this->getDataInicio());
        }

        return $interval;
    }
    
    /**
     * @param \DateInterval $tempoEspera
     * @return $this
     */
    public function setTempoDeslocamento(\DateInterval $tempoDeslocamento)
    {
        $this->tempoDeslocamento = $this->dateIntervalToSeconds($tempoDeslocamento);
        return $this;
    }

    /**
     * Retorna o tempo de deslocamento do cliente.
     * A diferença entre a data de chamada até a data de início.
     *
     * @return \DateInterval
     */
    public function getTempoDeslocamento()
    {
        if ($this->tempoDeslocamento) {
            return new \DateInterval("PT{$this->tempoDeslocamento}S");
        }
        
        $interval = new \DateInterval('P0M');
        if ($this->getDataChamada()) {
            $interval = $this->getDataInicio()->diff($this->getDataChamada());
        }

        return $interval;
    }

    /**
     * @return Cliente
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * @return Senha
     */
    public function getSenha()
    {
        return $this->senha;
    }
    
    public function getPrioridade()
    {
        return $this->prioridade;
    }

    public function setPrioridade(Prioridade $prioridade)
    {
        $this->prioridade = $prioridade;
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id'       => $this->getId(),
            'senha'    => $this->getSenha(),
            'servico'  => [
                'id'   => $this->getServico()->getId(),
                'nome' => $this->getServico()->getNome(),
            ],
            'dataChegada'     => $this->getDataChegada()->format('Y-m-d\TH:i:s'),
            'dataChamada'     => $this->getDataChamada() ? $this->getDataChamada()->format('Y-m-d\TH:i:s') : null,
            'dataInicio'      => $this->getDataInicio() ? $this->getDataInicio()->format('Y-m-d\TH:i:s') : null,
            'dataFim'         => $this->getDataFim() ? $this->getDataFim()->format('Y-m-d\TH:i:s') : null,
            'dataAgendamento' => $this->getDataAgendamento() ? $this->getDataAgendamento()->format('Y-m-d\TH:i:s') : null,
            'tempoEspera'     => $this->getTempoEspera()->format('%H:%I:%S'),
            'prioridade'      => $this->getPrioridade(),
            'status'          => $this->getStatus(),
            'cliente'         => $this->getCliente(),
            'triagem'         => $this->getUsuarioTriagem() ? $this->getUsuarioTriagem()->getUsername() : null,
            'usuario'         => $this->getUsuario() ? $this->getUsuario()->getUsername() : null,
        ];
    }

    public function __toString()
    {
        return $this->getSenha()->toString();
    }
    
    private function dateIntervalToSeconds(\DateInterval $d)
    {
        $seconds = $d->s + ($d->i * 60) + ($d->h * 3600) + ($d->d * 86400) + ($d->m * 2592000);
        
        return $seconds;
    }
}
