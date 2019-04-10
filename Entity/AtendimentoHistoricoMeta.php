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
 * AtendimentoMeta (Historico).
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class AtendimentoHistoricoMeta extends EntityMetadata
{
    public function getAtendimento()
    {
        return $this->getEntity();
    }

    public function setAtendimento(AtendimentoHistorico $atendimento): self
    {
        return $this->setEntity($atendimento);
    }
}
