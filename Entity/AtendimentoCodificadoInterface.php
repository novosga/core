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
interface AtendimentoCodificadoInterface
{
    public function getAtendimento(): AtendimentoInterface;

    public function getServico(): ServicoInterface;

    public function getPeso(): int;
}
