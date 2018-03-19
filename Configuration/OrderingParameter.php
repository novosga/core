<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Configuration;

use Doctrine\ORM\QueryBuilder;
use Novosga\Entity\Unidade;
use Novosga\Entity\Usuario;
use Novosga\Infrastructure\StorageInterface;

/**
 * OrderingParameter
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class OrderingParameter implements ParameterInterface
{
    /**
     * @var Unidade
     */
    private $unidade;
    
    /**
     * @var Usuario
     */
    private $usuario;
    
    /**
     * @var StorageInterface
     */
    private $storage;
    
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;
    
    public function getUnidade(): Unidade
    {
        return $this->unidade;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function getStorage(): StorageInterface
    {
        return $this->storage;
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    public function setUnidade(Unidade $unidade)
    {
        $this->unidade = $unidade;
        return $this;
    }

    public function setUsuario(?Usuario $usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
        return $this;
    }

    public function setQueryBuilder(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
        return $this;
    }
}