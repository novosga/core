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

namespace Novosga\Entity;

/**
 * EntityMetadataInterface.
 *
 * @template T of object
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface EntityMetadataInterface extends MetadataInterface
{
    /** @param ?T $entity */
    public function setEntity($entity): static;
    /** @return ?T */
    public function getEntity();
}
