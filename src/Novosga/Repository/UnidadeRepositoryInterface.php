<?php

namespace Novosga\Repository;

use Novosga\Entity\Usuario;
use Novosga\Entity\Unidade;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * UnidadeRepositoryInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface UnidadeRepositoryInterface extends ObjectRepository
{
    
    /**
     * Retorna as unidades disponíveis para o usuário
     * @param Usuario $usuario
     * @return Unidade[]
     */
    public function findByUsuario(Usuario $usuario);
}