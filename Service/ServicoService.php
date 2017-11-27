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
use Novosga\Entity\Servico;
use Novosga\Entity\ServicoMeta;
use Novosga\Entity\ServicoUnidade;
use Novosga\Entity\Unidade;
use Novosga\Entity\Usuario;

/**
 * ServicoService.
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
class ServicoService extends StorageAwareService
{
    /**
     * Cria ou retorna um metadado do serviço caso o $value seja null (ou ocultado).
     *
     * @param Servico $servico
     * @param string  $name
     * @param string  $value
     *
     * @return ServicoMeta
     */
    public function meta(Servico $servico, $name, $value = null)
    {
        $repo = $this->storage->getRepository(ServicoMeta::class);
        
        if ($value === null) {
            $metadata = $repo->get($servico, $name);
        } else {
            $metadata = $repo->set($servico, $name, $value);
        }
        
        return $metadata;
    }

    /**
     * Retorna a lista de serviços ativos.
     *
     * @param Unidade|int   $unidade
     * @param array         $where
     *
     * @return ArrayCollection
     */
    public function servicosUnidade($unidade, array $where = [])
    {
        $params = [
            'unidade' => $unidade,
        ];

        $qb = $this->storage
            ->getManager()
            ->createQueryBuilder()
            ->select('e')
            ->from(ServicoUnidade::class, 'e')
            ->join('e.servico', 's')
            ->where('e.unidade = :unidade')
            ->andWhere('s.deletedAt IS NULL')
            ->orderBy('s.nome', 'ASC');
        
        foreach ($where as $k => $v) {
            if (is_array($v)) {
                $qb->andWhere("e.{$k} IN (:{$k})");
            } else if (is_string($v)) {
                $qb->andWhere("e.{$k} LIKE :{$k}");
            } else {
                $qb->andWhere("e.{$k} = :{$k}");
            }
            $params[$k] = $v;
        }
                
        $servicos = $qb
            ->setParameters($params)
            ->getQuery()
            ->getResult();
                
        return $servicos;
    }

    /**
     * Retorna os servicos que o usuario nao atende na unidade atual.
     *
     * @param Unidade|int $unidade
     * @param Usuario|int $usuario
     *
     * @return ArrayCollection
     */
    public function servicosIndisponiveis($unidade, $usuario)
    {
        return $this->storage
            ->getManager()
            ->createQuery("
                SELECT
                    e
                FROM
                    Novosga\Entity\ServicoUnidade e
                    JOIN e.servico s
                WHERE
                    s.deletedAt IS NULL AND
                    e.ativo = TRUE AND
                    e.unidade = :unidade AND
                    s.id NOT IN (
                        SELECT s2.id
                        FROM Novosga\Entity\ServicoUsuario a
                        JOIN a.servico s2
                        WHERE a.usuario = :usuario AND a.unidade = :unidade
                    )
            ")
            ->setParameter('usuario', $usuario)
            ->setParameter('unidade', $unidade)
            ->getResult();
    }
}
