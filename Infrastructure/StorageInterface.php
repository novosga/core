<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Infrastructure;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Novosga\Entity\Agendamento;
use Novosga\Entity\Atendimento;
use Novosga\Entity\Unidade;

/**
 * StorageInterface
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface StorageInterface
{
    /**
     * @return ObjectManager
     */
    public function getManager(): ObjectManager;
    
    /**
     * @param string $className
     * @return ObjectRepository
     */
    public function getRepository(string $className): ObjectRepository;
    
    /**
     * Gera uma nova senha de atendimento
     * @param Atendimento $atendimento
     * @param Agendamento $agendamento
     */
    public function distribui(Atendimento $atendimento, Agendamento $agendamento = null);
    
    /**
     * @param Atendimento $atendimento
     */
    public function chamar(Atendimento $atendimento);
    
    /**
     * 
     * @param Atendimento $atendimento
     * @param array       $codificados
     * @param Atendimento $novoAtendimento
     */
    public function encerrar(Atendimento $atendimento, array $codificados, Atendimento $novoAtendimento = null);
    
    /**
     * Move os dados de atendimento para o histórico
     * @param Unidade $unidade
     */
    public function acumularAtendimentos(?Unidade $unidade, array $ctx = []);
    
    /**
     * Apaga todos os dados de atendimentos
     * @param Unidade $unidade
     */
    public function apagarDadosAtendimento(?Unidade $unidade, array $ctx = []);
}
