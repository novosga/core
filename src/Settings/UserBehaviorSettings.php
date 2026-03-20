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
 * UserBehaviorSettings
 *
 * Per-user overrides for behavior settings. A null value means
 * "inherit from the global BehaviorSettings default".
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class UserBehaviorSettings
{
    public function __construct(
        public ?bool $callTicketByService = null,
        public ?bool $callTicketOutOfOrder = null,
    ) {
    }
}
