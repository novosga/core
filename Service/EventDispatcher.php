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

use Novosga\Event\EventInterface;
use Novosga\Event\Event;
use Novosga\Event\ContainerAwareEvent;
use Psr\Container\ContainerInterface;

/**
 * EventDispatcher
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class EventDispatcher
{
    /**
     * @var Configuration
     */
    private $config;
    
    /**
     * @var ContainerInterface
     */
    private $container;
    
    public function __construct(Configuration $config, ContainerInterface $container)
    {
        $this->config    = $config;
        $this->container = $container;
    }
    
    /**
     * Dispatch event
     * 
     * @param EventInterface $event
     * @return bool
     */
    public function dispatch($event): bool
    {
        $eventName = $event->getName();
        $hookKey   = "hooks.{$eventName}";
        $callback  = $this->config->get($hookKey);
        
        if (is_callable($callback)) {
            return !!$callback($event);
        }
        
        return false;
    }
    
    /**
     * 
     * @param string $eventName
     * @param mixed  $eventData
     * @param bool   $containerAware
     */
    public function createAndDispatch(string $eventName, $eventData, bool $containerAware = false): bool
    {
        if ($containerAware) {
            $event = new ContainerAwareEvent($eventName, $eventData, $this->container);
        } else {
            $event = new Event($eventName, $eventData);
        }
        
        return $this->dispatch($event);
    }
}
