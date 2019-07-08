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
  * Senha enviada ao painel
  *
  * @author Rogerio Lino <rogeriolino@gmail.com>
  */
interface PainelSenhaInterface
{
    public function getId();

    public function getServico(): ServicoInterface;

    public function getUnidade(): UnidadeInterface;

    public function getNumeroSenha(): int;

    public function getSiglaSenha(): string;

    public function getMensagem(): string;

    public function getLocal(): string;

    public function getNumeroLocal(): int;

    public function getPeso(): int;

    public function getPrioridade(): string;

    public function getNomeCliente(): string;

    public function getDocumentoCliente(): string;
}
