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

use Doctrine\Common\Persistence\ObjectManager;

/**
 * ModelService.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
abstract class ModelService
{
    /**
     * @var ObjectManager
     */
    protected $em;

    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }
}
