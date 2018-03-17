<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Repository;

use Novosga\Entity\Metadata;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * MetadataRepositoryInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface MetadataRepositoryInterface extends ObjectRepository
{
    /**
     * @param mixed $entity
     * @param string $name
     * @return Metadata|null
     */
    public function get($entity, string $name);
    
    /**
     * @param mixed $entity
     * @param string $name
     * @param mixed $value
     * @return Metadata
     */
    public function set($entity, string $name, $value);
}