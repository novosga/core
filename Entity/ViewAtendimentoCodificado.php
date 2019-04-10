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
 * view Atendimento Codificado
 * União dos atendimentos atuais e do histórico
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class ViewAtendimentoCodificado extends AbstractAtendimentoCodificado
{
    /**
     * @var ViewAtendimento
     */
    private $atendimento;

    public function getAtendimento(): AbstractAtendimento
    {
        return $this->atendimento;
    }

    public function setAtendimento(AbstractAtendimento $atendimento): AbstractAtendimentoCodificado
    {
        $this->atendimento = $atendimento;

        return $this;
    }
}
