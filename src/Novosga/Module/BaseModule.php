<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Module;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * BaseModule
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
abstract class BaseModule extends Bundle implements ModuleInterface
{
    public function getKeyName() 
    {
        $namespace = $this->getNamespace();
        $tokens = explode('\\', str_replace('Bundle', '', $namespace));
        return strtolower(implode('.', $tokens));
    }
    
    public function getRoleName() 
    {
        $keyName = $this->getKeyName();
        return 'ROLE_' . strtoupper(str_replace('.', '_', $keyName));
    }
}
