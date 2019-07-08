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

use DateTimeInterface;
use DateInterval;
use Doctrine\Common\Collections\Collection;

/**
 * AtendimentoInterface
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface AtendimentoInterface
{   
    public function getId();

    public function getCodificados(): Collection;
    
    public function getUnidade(): ?UnidadeInterface;

    public function getServico(): ?ServicoInterface;

    public function getUsuario(): ?UsuarioInterface;

    public function getUsuarioTriagem(): ?UsuarioInterface;

    public function getLocal(): ?LocalInterface;

    public function getNumeroLocal(): ?int;
    
    public function getDataAgendamento(): ?DateTimeInterface;

    /**
     * @return DateTimeInterface
     */
    public function getDataChegada(): ?DateTimeInterface;

    /**
     * @return DateTimeInterface
     */
    public function getDataChamada(): ?DateTimeInterface;

    /**
     * @return DateTimeInterface
     */
    public function getDataInicio(): ?DateTimeInterface;

    /**
     * @return DateTimeInterface
     */
    public function getDataFim(): ?DateTimeInterface;

    public function getStatus();
    
    public function getResolucao();
    
    public function getPai(): ?self;

    /**
     * Retorna o tempo de espera do cliente até ser atendido.
     * A diferença entre a data de chegada até a data de chamada (ou atual).
     *
     * @return DateInterval
     */
    public function getTempoEspera(): DateInterval;
    
    /**
     * Retorna o tempo de permanência do cliente na unidade.
     * A diferença entre a data de chegada até a data de fim de atendimento.
     *
     * @return DateInterval
     */
    public function getTempoPermanencia(): DateInterval;

    /**
     * Retorna o tempo total do atendimento.
     * A diferença entre a data de início e fim do atendimento.
     *
     * @return DateInterval
     */
    public function getTempoAtendimento(): DateInterval;

    /**
     * Retorna o tempo de deslocamento do cliente.
     * A diferença entre a data de chamada até a data de início.
     *
     * @return \DateInterval
     */
    public function getTempoDeslocamento(): DateInterval;

    /**
     * @return ClienteInterface
     */
    public function getCliente(): ClienteInterface;

    /**
     * @return SenhaInterface
     */
    public function getSenha(): SenhaInterface;
    
    public function getPrioridade(): PrioridadeInterface;
    
    public function getObservacao(): ?string;
}
