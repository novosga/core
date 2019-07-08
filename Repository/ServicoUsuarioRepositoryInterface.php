<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Repository;

use Novosga\Entity\UsuarioInterface;
use Novosga\Entity\UnidadeInterface;
use Novosga\Entity\ServicoInterface;
use Novosga\Entity\ServicoUsuarioInterface;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * ServicoUsuarioRepositoryInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface ServicoUsuarioRepositoryInterface extends ObjectRepository
{
    /**
     * Retorna todos os serviços do usuario
     *
     * @param UsuarioInterface|int $usuario
     * @return ServicoUsuarioInterface[]
     */
    public function getAll($usuario, $unidade);
    
    /**
     * Retorna o relacionamento entre o serviço e a usuario.
     *
     * @param UsuarioInterface|int $usuario
     * @param UnidadeInterface|int $unidade
     * @param ServicoInterface|int $servico
     * @return ServicoUsuarioInterface|null
     */
    public function get($usuario, $unidade, $servico);
}
