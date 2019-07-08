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

use Novosga\Entity\MetadataInterface;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * MetadataRepositoryInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface MetadataRepositoryInterface extends ObjectRepository
{
    /**
     * @param string $namespace
     * @param string $name
     * @return MetadataInterface|null
     */
    public function get(string $namespace, string $name);
    
    /**
     * @param string $namespace
     * @param string $name
     * @param mixed $value
     * @return MetadataInterface
     */
    public function set(string $namespace, string $name, $value);
}
