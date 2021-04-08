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

use Novosga\Entity\EntityMetadata;
use Doctrine\Persistence\ObjectRepository;

/**
 * MetadataRepositoryInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface EntityMetadataRepositoryInterface extends ObjectRepository
{

    /**
     * @param mixed $entity
     * @param string $namespace
     * @return EntityMetadata[]
     */
    public function findByNamespace($entity, string $namespace);

    /**
     * @param mixed $entity
     * @param string $namespace
     * @param string $name
     * @return EntityMetadata|null
     */
    public function get($entity, string $namespace, string $name);
    
    /**
     * @param mixed $entity
     * @param string $namespace
     * @param string $name
     * @param mixed $value
     * @return EntityMetadata
     */
    public function set($entity, string $namespace, string $name, $value);
}
