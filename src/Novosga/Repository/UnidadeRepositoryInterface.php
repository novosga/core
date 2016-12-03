<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Repository;

use Novosga\Entity\Usuario;
use Novosga\Entity\Unidade;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * UnidadeRepositoryInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface UnidadeRepositoryInterface extends ObjectRepository
{
    
    /**
     * Retorna as unidades disponíveis para o usuário
     * @param Usuario $usuario
     * @return Unidade[]
     */
    public function findByUsuario(Usuario $usuario);
}