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

use DateTimeInterface;
use Doctrine\Persistence\ObjectRepository;
use Novosga\Entity\AgendamentoInterface;
use Novosga\Entity\ServicoInterface;
use Novosga\Entity\UnidadeInterface;

/**
 * AgendamentoRepositoryInterface
 *
 * @extends ObjectRepository<AgendamentoInterface>
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface AgendamentoRepositoryInterface extends ObjectRepository, BaseRepository
{
    /** @return AgendamentoInterface[] */
    public function findByUnidadeAndServicoAndData(
        UnidadeInterface|int $unidade,
        ServicoInterface|int $servico,
        DateTimeInterface $data,
    ): array;
}
