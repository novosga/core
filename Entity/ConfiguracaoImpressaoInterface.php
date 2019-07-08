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

interface ConfiguracaoImpressaoInterface
{
    public function getUnidade(): UnidadeInterface;

    public function getCabecalho(): string;

    public function getRodape(): string;

    public function getExibirNomeServico(): bool;

    public function getExibirNomeUnidade(): bool;

    public function getExibirMensagemServico(): bool;

    public function getExibirData(): bool;

    public function getExibirPrioridade(): bool;
}
