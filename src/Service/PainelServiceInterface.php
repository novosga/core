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

use Novosga\Entity\PainelInterface;
use Novosga\Entity\UnidadeInterface;
use Novosga\Settings\PainelSettings;

/**
 * PainelServiceInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface PainelServiceInterface
{
    public function getById(int $id): ?PainelInterface;

    public function getByPublicId(string $publicId): ?PainelInterface;

    /** @return PainelInterface[] */
    public function findByUnidade(UnidadeInterface $unidade): array;

    public function build(): PainelInterface;

    public function save(PainelInterface $painel): PainelInterface;

    public function remove(PainelInterface $painel): void;

    public function loadSettings(PainelInterface $painel): PainelSettings;

    public function saveSettings(PainelInterface $painel, PainelSettings $settings): void;
}
