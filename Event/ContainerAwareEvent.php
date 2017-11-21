<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Event;

use Psr\Container\ContainerInterface;

/**
 * ContainerAwareEvent
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class ContainerAwareEvent extends Event
{
    /**
     * @var ContainerInterface
     */
    private $container;
    
    public function __construct(string $name, $data, ContainerInterface $container)
    {
        parent::__construct($name, $data);
        
        $this->container = $container;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
