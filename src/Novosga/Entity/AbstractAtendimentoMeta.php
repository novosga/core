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
 * AbstractAtendimentoMeta
 * Atendimento metadata.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
abstract class AbstractAtendimentoMeta extends Metadata
{
    abstract public function getAtendimento();

    abstract public function setAtendimento(AbstractAtendimento $atendimento);

    public function getEntity()
    {
        return $this->getAtendimento();
    }

    public function setEntity($entity)
    {
        $this->setAtendimento($entity);
    }
}
