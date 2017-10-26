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
 * Configuration
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class Configuration
{
    /**
     * @var string
     */
    private $default;
    
    /**
     * @var string
     */
    private $custom;
    
    public function __construct($rootDir)
    {
        $this->default = require("{$rootDir}/config/app.default.php");
        $this->custom  = @require("{$rootDir}/config/app.default.php");
        
        if (!$this->custom) {
            $this->custom = [];
        }
    }
    
    public function get($key)
    {
        return null;
    }
}
