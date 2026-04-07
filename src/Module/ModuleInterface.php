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

namespace Novosga\Module;

/**
 * ModuleInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface ModuleInterface
{
    public function getKeyName(): string;

    public function getRoleName(): string;

    public function getIconName(): string;

    public function getDisplayName(): string;

    public function getName(): string;

    public function getHomeRoute(): string;

    public function getDescription(): ?string;

    public function getWebsite(): ?string;

    public function getAuthor(): ?string;
}
