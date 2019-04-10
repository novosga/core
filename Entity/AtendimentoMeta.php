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
 * AtendimentoMeta.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class AtendimentoMeta extends EntityMetadata
{
    public function getAtendimento()
    {
        return $this->getEntity();
    }

    public function setAtendimento(Atendimento $atendimento): self
    {
        return $this->setEntity($atendimento);
    }
}
