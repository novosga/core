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
 * Classe Senha
 * Responsavel pelas informacoes do Senha.
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface SenhaInterface
{
    const LENGTH = 3;
    
    /**
     * Retorna a sigla da senha.
     *
     * @return string
     */
    public function getSigla(): string;

    /**
     * Retorna o numero da senha.
     *
     * @return int $numero
     */
    public function getNumero(): int;
}
