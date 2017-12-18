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

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ObjectRepository;
use Novosga\Entity\Unidade;
use Novosga\Entity\Usuario;

/**
 * UsuarioRepositoryInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface UsuarioRepositoryInterface extends ObjectRepository
{
    /**
     * Retorna os usuários que tem lotação na unidade
     * @param Unidade $unidade
     * @return Usuario[]
     */
    public function findByUnidade(Unidade $unidade, Criteria $criteria = null);
}
