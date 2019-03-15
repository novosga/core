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

use Novosga\Entity\Unidade;
use Novosga\Entity\Servico;
use Novosga\Entity\ServicoUnidade;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * ServicoUnidadeRepositoryInterface
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
interface ServicoUnidadeRepositoryInterface extends ObjectRepository
{
    /**
     * Retorna todos os serviços da unidade
     *
     * @param Unidade|int $unidade
     * @return ServicoUnidade[]
     */
    public function getAll($unidade);
    
    /**
     * Retorna o relacionamento entre o serviço e a unidade.
     *
     * @param Unidade|int $unidade
     * @param Servico|int $servico
     * @return ServicoUnidade|null
     */
    public function get($unidade, $servico);
}
