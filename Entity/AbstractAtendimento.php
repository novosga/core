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
use Novosga\Service\AtendimentoService;

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
     * @var ServicoUnidade
     */
    private $servicoUnidade;

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

    public function getServicoUnidade()
    {
        return $this->servicoUnidade;
    }

    public function setServicoUnidade(ServicoUnidade $servicoUnidade)
    {
        $this->servicoUnidade = $servicoUnidade;
        $this->setServico($servicoUnidade->getServico());
        $this->setUnidade($servicoUnidade->getUnidade());

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

    /**
     * Retorna o nome do status do atendimento.
     *
     * @return type
     */
    public function getNomeStatus()
    {
        return AtendimentoService::nomeSituacao($this->getStatus());
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
     * Retorna o tempo de espera do cliente até ser atendido.
     * A diferença entre a data de chegada até a data atual.
     *
     * @return \DateInterval
     */
    public function getTempoEspera()
    {
        $now = new \DateTime();
        $interval = $now->diff($this->getDataChegada());

        return $interval;
    }

    /**
     * Retorna o tempo de permanência do cliente na unidade.
     * A diferença entre a data de chegada até a data de fim de atendimento.
     *
     * @return \DateInterval
     */
    public function getTempoPermanencia()
    {
        $interval = new \DateInterval('P0M');
        if ($this->getDataFim()) {
            $interval = $this->getDataFim()->diff($this->getDataChegada());
        }

        return $interval;
    }

    /**
     * Retorna o tempo total do atendimento.
     * A diferença entre a data de início e fim do atendimento.
     *
     * @return \DateInterval
     */
    public function getTempoAtendimento()
    {
        $interval = new \DateInterval('P0M');
        if ($this->getDataFim()) {
            $interval = $this->getDataFim()->diff($this->getDataInicio());
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

    public function jsonSerialize($minimal = false)
    {
        $arr = [
            'id'             => $this->getId(),
            'senha'          => $this->getSenha(),
            'servico'        => [
                'id'   => $this->getServicoUnidade()->getServico()->getId(),
                'nome' => $this->getServicoUnidade()->getServico()->getNome(),
            ],
            'chegada'        => $this->getDataChegada()->format('Y-m-d H:i:s'),
            'espera'         => $this->getTempoEspera()->format('%H:%I:%S'),
            'prioridade'     => $this->getPrioridade(),
        ];
        if (!$minimal) {
            $arr['numero'] = $this->getSenha()->getNumero();
            if ($this->getUsuario()) {
                $arr['usuario'] = $this->getUsuario()->getLogin();
            }
            if ($this->getUsuarioTriagem()) {
                $arr['triagem'] = $this->getUsuarioTriagem()->getLogin();
            }
            if ($this->getDataInicio()) {
                $arr['inicio'] = $this->getDataInicio()->format('Y-m-d H:i:s');
            }
            if ($this->getDataChamada()) {
                $arr['chamada'] = $this->getDataChamada()->format('Y-m-d H:i:s');
            }
            if ($this->getDataAgendamento()) {
                $arr['agendamento'] = $this->getDataAgendamento()->format('Y-m-d H:i:s');
            }
            if ($this->getDataFim()) {
                $arr['fim'] = $this->getDataFim()->format('Y-m-d H:i:s');
            }
            $arr['status'] = $this->getStatus();
            $arr['nomeStatus'] = $this->getNomeStatus();
            $arr['cliente'] = $this->getCliente();
        }

        return $arr;
    }

    public function __toString()
    {
        return $this->getSenha()->toString();
    }
}
