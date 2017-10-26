<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Service;

/**
 * Dispatcher
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class Dispatcher
{
    /**
     * @var Configuration
     */
    private $config;
    
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }
    
    /**
     * 
     * @param string $name
     * @param mixed  $args
     */
    public function dispatch($name, $args)
    {
        
    }
}
