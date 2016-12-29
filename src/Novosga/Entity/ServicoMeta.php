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
 * Servico metadata.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class ServicoMeta extends Metadata
{
    /**
     * @var Servico
     */
    private $servico;

    public function getEntity()
    {
        return $this->getServico();
    }

    public function setEntity($entity)
    {
        $this->setServico($entity);
    }

    public function getServico()
    {
        return $this->servico;
    }

    public function setServico(Servico $servico)
    {
        $this->servico = $servico;

        return $this;
    }
}
