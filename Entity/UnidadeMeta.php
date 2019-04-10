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
 * Unidade metadata.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class UnidadeMeta extends EntityMetadata
{
    public function getUnidade()
    {
        return $this->getEntity();
    }

    public function setUnidade(Unidade $unidade): self
    {
        return $this->setEntity($unidade);
    }
}
