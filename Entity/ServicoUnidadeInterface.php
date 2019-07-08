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
 * Servico Unidade
 * Configuração do serviço na unidade
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface ServicoUnidadeInterface
{
    /**
     * O atendimento do serviço pode ser tanto normal quanto prioridade
     */
    const ATENDIMENTO_TODOS      = 1;

    /**
     * O atendimento do serviço só poder ser normal
     */
    const ATENDIMENTO_NORMAL     = 2;

    /**
     * O atendimento do serviço só poder ser prioridade
     */
    const ATENDIMENTO_PRIORIDADE = 3;

    /**
     * @return ServicoInterface
     */
    public function getServico(): ?ServicoInterface;

    /**
     * @return UnidadeInterface
     */
    public function getUnidade(): ?UnidadeInterface;

    public function getDepartamento(): ?DepartamentoInterface;

    /**
     * @return LocalInterface
     */
    public function getLocal(): ?LocalInterface;

    public function isAtivo(): bool;

    public function getPeso(): int;

    public function getSigla(): string;

    public function getTipo(): int;

    public function getIncremento(): int;

    public function getNumeroInicial(): int;

    public function getNumeroFinal(): ?int;

    public function getMaximo(): ?int;

    public function getMensagem(): ?string;
}
