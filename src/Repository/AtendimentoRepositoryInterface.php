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

use Doctrine\Persistence\ObjectRepository;
use Novosga\Entity\AtendimentoInterface;
use Novosga\Entity\ServicoInterface;
use Novosga\Entity\UnidadeInterface;

/**
 * AtendimentoRepositoryInterface
 *
 * @extends ObjectRepository<AtendimentoInterface>
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface AtendimentoRepositoryInterface extends ObjectRepository, BaseRepository
{
    /**
     * Retorna o par do id do serviço e o total de atendimentos
     * @param ServicoInterface[]|int[] $servicos
     * @return array<string,mixed>
     */
    public function countByServicos(UnidadeInterface $unidade, array $servicos, ?string $status = null): array;

    public function getUltimo(UnidadeInterface $unidade, ?ServicoInterface $servico = null): ?AtendimentoInterface;
}
