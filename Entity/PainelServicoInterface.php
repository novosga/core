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
  * PainelServico
  *
  * @author Rogerio Lino <rogeriolino@gmail.com>
  */
interface PainelServicoInterface
{
    public function getPainel(): PainelInterface;

    public function getServico(): ServicoInterface;

    public function getUnidade(): UnidadeInterface;
}
