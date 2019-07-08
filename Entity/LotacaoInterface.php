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
 * Definição de onde o usuário está lotado
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface LotacaoInterface
{
    
    public function getId();

    /**
     * Retorna objeto usuario.
     *
     * @return UsuarioInterface
     */
    public function getUsuario(): UsuarioInterface;

    /**
     * Retorna objeto Unidade.
     *
     * @return UnidadeInterface
     */
    public function getUnidade(): UnidadeInterface;

    /**
     * Retorna objeto Perfil.
     *
     * @return Perfil $perfil
     */
    public function getPerfil(): PerfilInterface;
}
