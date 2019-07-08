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
 * Servico Usuario
 * Configuração do serviço que o usuário atende
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface ServicoUsuarioInterface
{   
    public function getServico(): ServicoInterface;

    public function getUnidade(): UnidadeInterface;

    /**
     * @return Usuario
     */
    public function getUsuario(): UsuarioInterface;
    
    public function getPeso(): int;
}
