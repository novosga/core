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
use Novosga\Entity\AgendamentoInterface;
use Novosga\Entity\AtendimentoInterface;
use Novosga\Entity\UnidadeInterface;

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
     * @param AtendimentoInterface $atendimento
     * @param AgendamentoInterface $agendamento
     */
    public function distribui(AtendimentoInterface $atendimento, AgendamentoInterface $agendamento = null);
    
    /**
     * @param AtendimentoInterface $atendimento
     */
    public function chamar(AtendimentoInterface $atendimento);
    
    /**
     * 
     * @param AtendimentoInterface $atendimento
     * @param array       $codificados
     * @param AtendimentoInterface $novoAtendimento
     */
    public function encerrar(AtendimentoInterface $atendimento, array $codificados, AtendimentoInterface $novoAtendimento = null);
    
    /**
     * Move os dados de atendimento para o histórico
     * @param UnidadeInterface $unidade
     */
    public function acumularAtendimentos(?UnidadeInterface $unidade, array $ctx = []);
    
    /**
     * Apaga todos os dados de atendimentos
     * @param UnidadeInterface $unidade
     */
    public function apagarDadosAtendimento(?UnidadeInterface $unidade, array $ctx = []);
}
