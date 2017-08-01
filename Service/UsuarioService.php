<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Novosga\Entity\Unidade;
use Novosga\Entity\Usuario;
use Novosga\Entity\ServicoUsuario;

/**
 * UsuarioService.
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
class UsuarioService extends MetaModelService
{
    const ATTR_ATENDIMENTO_LOCAL = 'atendimento.local';
    const ATTR_ATENDIMENTO_TIPO = 'atendimento.tipo';
    const ATTR_UNIDADE = 'unidade';

    protected function getMetaClass()
    {
        return 'Novosga\Entity\UsuarioMeta';
    }

    protected function getMetaFieldname()
    {
        return 'usuario';
    }

    /**
     * Cria ou retorna um metadado do usuário caso o $value seja null (ou ocultado).
     *
     * @param Usuario $usuario
     * @param string  $name
     * @param string  $value
     *
     * @return \Novosga\Entity\UsuarioMeta
     */
    public function meta(Usuario $usuario, $name, $value = null)
    {
        return $this->modelMetadata($usuario, $name, $value);
    }

    /**
     * Retorna a lista de serviços que o usuário atende na determinada unidade.
     *
     * @param Usuario $usuario
     * @param Unidade $unidade
     *
     * @return ArrayCollection
     */
    public function servicos(Usuario $usuario, Unidade $unidade)
    {
        $servicos = $this->em
                ->createQueryBuilder()
                ->select('e')
                ->from(ServicoUsuario::class, 'e')
                ->join('e.servico', 's')
                ->where('e.usuario = :usuario AND e.unidade = :unidade AND s.ativo = TRUE')
                ->orderBy('s.nome', 'ASC')
                ->setParameters([
                    'usuario' => $usuario,
                    'unidade' => $unidade
                ])
                ->getQuery()
                ->getResult();
        
        return $servicos;
    }

    public function isLocalLivre($unidade, $usuario, $numero)
    {
        $count = (int) $this->em
                ->createQuery('
                    SELECT
                        COUNT(1)
                    FROM
                        Novosga\Entity\UsuarioMeta e
                    WHERE
                        (e.name = :metaLocal AND e.value = :numero AND e.usuario != :usuario)
                        AND EXISTS (SELECT e2 FROM Novosga\Entity\UsuarioMeta e2 WHERE e2.name = :metaUnidade AND e2.value = :unidade AND e2.usuario = e.usuario)
                ')
                ->setParameters([
                    'metaLocal'   => self::ATTR_ATENDIMENTO_LOCAL,
                    'numero'      => $numero,
                    'usuario'     => $usuario,
                    'metaUnidade' => self::ATTR_UNIDADE,
                    'unidade'     => $unidade,
                ])
                ->getSingleScalarResult();

        return $count === 0;
    }
}
