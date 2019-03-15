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

use Novosga\Entity\Usuario;
use Novosga\Entity\Unidade;
use Novosga\Entity\Servico;
use Novosga\Entity\ServicoUsuario;
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
     * @param Usuario|int $usuario
     * @return ServicoUsuario[]
     */
    public function getAll($usuario, $unidade);
    
    /**
     * Retorna o relacionamento entre o serviço e a usuario.
     *
     * @param Usuario|int $usuario
     * @param Unidade|int $unidade
     * @param Servico|int $servico
     * @return ServicoUsuario|null
     */
    public function get($usuario, $unidade, $servico);
}
