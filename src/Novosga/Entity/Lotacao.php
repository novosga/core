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
class Lotacao implements \JsonSerializable
{
    /**
     * @var Usuario
     */
    private $usuario;

    /**
     * @var Unidade
     */
    private $unidade;

    /**
     * @var Cargo
     */
    private $cargo;

    public function __construct()
    {
    }

    /**
     * Modifica usuario.
     *
     * @param $usuario
     *
     * @return none
     */
    public function setUsuario(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * Retorna objeto usuario.
     *
     * @return Usuario $usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Modifica unidade.
     *
     * @param $unidade
     */
    public function setUnidade(Unidade $unidade)
    {
        $this->unidade = $unidade;
    }

    /**
     * Retorna objeto Unidade.
     *
     * @return Unidade
     */
    public function getUnidade()
    {
        return $this->unidade;
    }

    /**
     * Modifica cargo.
     *
     * @param $cargo
     *
     * @return none
     */
    public function setCargo(Cargo $cargo)
    {
        $this->cargo = $cargo;
    }

    /**
     * Retorna objeto Cargo.
     *
     * @return Cargo $cargo
     */
    public function getCargo()
    {
        return $this->cargo;
    }

    public function jsonSerialize()
    {
        return [
            'cargo'   => $this->getCargo(),
            'unidade'   => $this->getUnidade(),
            'usuario' => $this->getUsuario(),
        ];
    }
}
