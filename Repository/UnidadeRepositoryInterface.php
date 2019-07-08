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

use Novosga\Entity\UsuarioInterface;
use Novosga\Entity\UnidadeInterface;
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
     * @param UsuarioInterface $usuario
     * @return UnidadeInterface[]
     */
    public function findByUsuario(UsuarioInterface $usuario);
}
