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
     * @var int
     */
    private $id;
    
    /**
     * @var Usuario
     */
    private $usuario;

    /**
     * @var Unidade
     */
    private $unidade;

    /**
     * @var Perfil
     */
    private $perfil;

    public function __construct()
    {
    }
    
    public function getId()
    {
        return $this->id;
    }

    /**
     * Modifica usuario.
     *
     * @param $usuario
     *
     * @return none
     */
    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
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
    public function setUnidade(Unidade $unidade): self
    {
        $this->unidade = $unidade;

        return $this;
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
     * Modifica perfil.
     *
     * @param $perfil
     *
     * @return none
     */
    public function setPerfil(Perfil $perfil): self
    {
        $this->perfil = $perfil;

        return $this;
    }

    /**
     * Retorna objeto Perfil.
     *
     * @return Perfil $perfil
     */
    public function getPerfil()
    {
        return $this->perfil;
    }

    public function jsonSerialize()
    {
        return [
            'id'      => $this->getId(),
            'perfil'  => $this->getPerfil(),
            'unidade' => $this->getUnidade(),
            'usuario' => $this->getUsuario(),
        ];
    }
}
