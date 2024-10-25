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

namespace Novosga\Service;

use Doctrine\ORM\QueryBuilder;
use Novosga\Entity\UnidadeInterface;
use Novosga\Entity\UsuarioInterface;

/**
 * QueueOrderingServiceInterface.
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface QueueOrderingServiceInterface
{
    public function applyOrder(
        QueryBuilder $queryBuilder,
        UnidadeInterface $unidade,
        ?UsuarioInterface $usuario
    ): void;
}
