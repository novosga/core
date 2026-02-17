<?php

declare(strict_types=1);

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
use Doctrine\Persistence\ObjectRepository;
use Novosga\Entity\UnidadeInterface;
use Novosga\Entity\UsuarioInterface;
use Novosga\Entity\ServicoUnidadeInterface;

/**
 * UsuarioRepositoryInterface
 *
 * @extends ObjectRepository<UsuarioInterface>
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface UsuarioRepositoryInterface extends ObjectRepository, BaseRepository
{
    /**
     * Retorna os usuários que tem lotação na unidade
     * @return UsuarioInterface[]
     */
    public function findByUnidade(UnidadeInterface $unidade, ?Criteria $criteria = null): array;

    /**
     * Retorna os usuários que atendem o serviço da unidade
     * @return UsuarioInterface[]
     */
    public function findByServicoUnidade(ServicoUnidadeInterface $servicoUnidade, ?Criteria $criteria = null): array;
}
