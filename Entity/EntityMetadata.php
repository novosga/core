<?php

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
 * Abstract metadata.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
abstract class EntityMetadata extends AbstractMetadata
{
    /**
     * @var mixed
     */
    protected $entity;
    
    public function setEntity($entity): self
    {
        $this->entity = $entity;
        
        return $this;
    }
    
    public function getEntity()
    {
        return $this->entity;
    }
}
