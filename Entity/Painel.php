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
  * Painel
  *
  * @author Rogerio Lino <rogeriolino@gmail.com>
  */
class Painel implements \JsonSerializable
{
    /**
     * @var int
     */
    private $host;

    /**
     * @var string
     */
    private $senha;

    /**
     * @var Unidade
     */
    private $unidade;

    /**
     * @var PainelServico[]
     */
    private $servicos;

    public function getHost()
    {
        return $this->host;
    }

    public function setHost($host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getUnidade()
    {
        return $this->unidade;
    }

    public function setUnidade($unidade): self
    {
        $this->unidade = $unidade;

        return $this;
    }

    public function getServicos()
    {
        return $this->servicos;
    }

    public function setServicos($servicos): self
    {
        $this->servicos = $servicos;
        
        return $this;
    }

    public function getIp()
    {
        return long2ip($this->getHost());
    }

    public function __toString()
    {
        return $this->getIp();
    }

    public function jsonSerialize()
    {
        return [
           'host'     => $this->getHost(),
           'ip'       => $this->getIp(),
           'servicos' => $this->getServicos(),
        ];
    }
}
