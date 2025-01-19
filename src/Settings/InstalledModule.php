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

namespace Novosga\Settings;

/**
 * InstalledModule
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
final class InstalledModule
{
    public function __construct(
        public readonly bool $active,
        public readonly string $key,
        public readonly string $displayName,
        public readonly string $iconName,
        public readonly string $homeRoute,
    ) {
    }
}
