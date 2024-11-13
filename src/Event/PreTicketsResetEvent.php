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

namespace Novosga\Event;

use DateTimeInterface;
use Novosga\Entity\UnidadeInterface;
use Novosga\Entity\UsuarioInterface;

/**
 * PreTicketsResetEvent
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
final readonly class PreTicketsResetEvent
{
    public function __construct(
        public ?UnidadeInterface $unidade,
        public ?UsuarioInterface $usuario,
        public DateTimeInterface $ateData,
    ) {
    }
}
