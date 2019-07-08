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
use Novosga\Entity\UnidadeInterface;
use Novosga\Entity\ServicoUnidadeInterface;

/**
 * UsuarioRepositoryInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface UsuarioRepositoryInterface extends ObjectRepository
{
    /**
     * Retorna os usuários que tem lotação na unidade
     * @param UnidadeInterface $unidade
     * @param Criteria $criteria
     * @return UsuarioInterface[]
     */
    public function findByUnidade(UnidadeInterface $unidade, Criteria $criteria = null);
    
    /**
     * Retorna os usuários que atendem o serviço da unidade
     * @param ServicoUnidadeInterface $servicoUnidade
     * @param Criteria $criteria
     * @return UsuarioInterface[]
     */
    public function findByServicoUnidade(ServicoUnidadeInterface $servicoUnidade, Criteria $criteria = null);
}
