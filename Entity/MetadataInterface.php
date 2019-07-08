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
 * MetadataInterface
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface MetadataInterface
{   
    public function getNamespace(): string;
        
    public function getName(): string;

    public function getValue();
}
