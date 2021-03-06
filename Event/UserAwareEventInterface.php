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

use Novosga\Entity\Usuario;

/**
 * UserAwareEventInterface
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
interface UserAwareEventInterface extends EventInterface
{
    /**
     * @param Usuario $user
     */
    public function setUser(Usuario $user);
    
    /**
     * @return Usuario
     */
    public function getUser(): Usuario;
}
