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

use Psr\Log\LoggerInterface;

/**
 * LoggerAwareEventInterface
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface LoggerAwareEventInterface extends EventInterface
{
    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger);
    
    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface;
}
