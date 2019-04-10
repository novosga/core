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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Classe Atendimento
 * contem o Cliente, o Servico e o Status do atendimento.
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class Atendimento extends AbstractAtendimento
{
    /**
     * @var AtendimentoCodificado[]
     */
    private $codificados;

    public function __construct()
    {
        parent::__construct();
        $this->codificados = new ArrayCollection();
    }

    public function getCodificados()
    {
        return $this->codificados;
    }

    public function setCodificados(Collection $codificados): self
    {
        $this->codificados = $codificados;

        return $this;
    }

    /**
     * Atendimento hash.
     *
     * @return string
     */
    public function hash()
    {
        return sha1("{$this->getId()}:{$this->getDataChegada()->getTimestamp()}");
    }

    public function jsonSerialize($minimal = false)
    {
        $arr = parent::jsonSerialize($minimal);
        $arr['hash'] = $this->hash();

        return $arr;
    }
}
