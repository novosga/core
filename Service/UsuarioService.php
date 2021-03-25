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
use Novosga\Entity\ServicoUsuario;
use Novosga\Entity\Unidade;
use Novosga\Entity\Usuario;
use Novosga\Entity\Servico;
use Novosga\Entity\UsuarioMeta;
use Novosga\Infrastructure\StorageInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

/**
 * UsuarioService.
 *
 * @author Rogério Lino <rogeriolino@gmail.com>
 */
class UsuarioService extends StorageAwareService
{
    const ATTR_NAMESPACE             = 'global';
    const ATTR_ATENDIMENTO_LOCAL     = 'atendimento.local';
    const ATTR_ATENDIMENTO_NUM_LOCAL = 'atendimento.num_local';
    const ATTR_ATENDIMENTO_TIPO      = 'atendimento.tipo';
    const ATTR_SESSION_UNIDADE       = 'session.unidade';

    /**
     * @var PublisherInterface
     */
    private $publisher;

    public function __construct(StorageInterface $storage, PublisherInterface $publisher)
    {
        parent::__construct($storage);
        $this->publisher = $publisher;
    }

    /**
     * Cria ou retorna um metadado do usuário caso o $value seja null (ou ocultado).
     *
     * @param Usuario $usuario
     * @param string  $name
     * @param string  $value
     *
     * @return UsuarioMeta
     */
    public function meta(Usuario $usuario, $name, $value = null)
    {
        $repo = $this->storage->getRepository(UsuarioMeta::class);
        
        if ($value === null) {
            $metadata = $repo->get($usuario, self::ATTR_NAMESPACE, $name);
        } else {
            $metadata = $repo->set($usuario, self::ATTR_NAMESPACE, $name, $value);
        }
        
        return $metadata;
    }

    /**
     * Retorna a lista de serviços que o usuário atende na determinada unidade.
     *
     * @param Usuario $usuario
     * @param Unidade $unidade
     *
     * @return ArrayCollection
     */
    public function servico(Usuario $usuario, Servico $servico, Unidade $unidade)
    {
        $servico = $this->storage
            ->getManager()
            ->createQueryBuilder()
            ->select('e')
            ->from(ServicoUsuario::class, 'e')
            ->join('e.servico', 's')
            ->where('e.usuario = :usuario')
            ->andWhere('e.servico = :servico')
            ->andWhere('e.unidade = :unidade')
            ->andWhere('s.ativo = TRUE')
            ->orderBy('s.nome', 'ASC')
            ->setParameters([
                'usuario' => $usuario,
                'servico' => $servico,
                'unidade' => $unidade
            ])
            ->getQuery()
            ->getOneOrNullResult();
        
        return $servico;
    }

    /**
     * Retorna a lista de serviços que o usuário atende na determinada unidade.
     *
     * @param Usuario $usuario
     * @param Unidade $unidade
     *
     * @return array
     */
    public function servicos(Usuario $usuario, Unidade $unidade)
    {
        $servicos = $this->storage
            ->getManager()
            ->createQueryBuilder()
            ->select('e')
            ->from(ServicoUsuario::class, 'e')
            ->join('e.servico', 's')
            ->where('e.usuario = :usuario')
            ->andWhere('e.unidade = :unidade')
            ->andWhere('s.ativo = TRUE')
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
        $count = (int) $this->storage
            ->getManager()
            ->createQuery('
                SELECT
                    COUNT(1)
                FROM
                    Novosga\Entity\UsuarioMeta e
                WHERE
                    (e.name = :metaLocal AND e.value = :numero AND e.usuario != :usuario)
                    AND EXISTS (
                        SELECT e2
                        FROM Novosga\Entity\UsuarioMeta e2
                        WHERE
                            e2.name = :metaUnidade AND
                            e2.value = :unidade AND
                            e2.usuario = e.usuario
                    )
            ')
            ->setParameters([
                'metaLocal'   => self::ATTR_ATENDIMENTO_LOCAL,
                'numero'      => $numero,
                'usuario'     => $usuario,
                'metaUnidade' => self::ATTR_SESSION_UNIDADE,
                'unidade'     => $unidade,
            ])
            ->getSingleScalarResult();

        return $count === 0;
    }

    public function updateUsuario(
        Usuario $usuario,
        string $tipoAtendimento = null,
        int $local = null,
        int $numero = null,
    ) {
        $em = $this->storage->getManager();

        if ($tipoAtendimento && in_array($tipoAtendimento, FilaService::TIPOS_ATENDIMENTO)) {
            $this->meta($usuario, UsuarioService::ATTR_ATENDIMENTO_TIPO, $tipoAtendimento);
        }
        
        if ($local > 0) {
            $this->meta($usuario, UsuarioService::ATTR_ATENDIMENTO_LOCAL, $local);
        }
        
        if ($numero > 0) {
            $this->meta($usuario, UsuarioService::ATTR_ATENDIMENTO_NUM_LOCAL, $numero);
        }
        
        ($this->publisher)(new Update([
            "/usuarios/{$usuario->getId()}/fila",
        ], json_encode([ 'id' => $usuario->getId() ])));
    }

    public function addServicoUsuario(Usuario $usuario, Servico $servico, Unidade $unidade): ServicoUsuario
    {
        $em = $this->storage->getManager();

        $servicoUsuario = new ServicoUsuario();
        $servicoUsuario->setUsuario($usuario);
        $servicoUsuario->setServico($servico);
        $servicoUsuario->setUnidade($unidade);
        $servicoUsuario->setPeso(1);

        $em->persist($servicoUsuario);
        $em->flush();
        
        ($this->publisher)(new Update([
            "/usuarios/{$usuario->getId()}/fila",
        ], json_encode([ 'id' => $usuario->getId() ])));

        return $servicoUsuario;
    }

    public function removeServicoUsuario(Usuario $usuario, Servico $servico, Unidade $unidade): ?ServicoUsuario
    {
        $em = $this->storage->getManager();

        $servicoUsuario = $em
            ->getRepository(ServicoUsuario::class)
            ->findOneBy([
                'usuario' => $usuario,
                'servico' => $servico,
                'unidade' => $unidade
            ]);

        if ($servicoUsuario) {
            $em->remove($servicoUsuario);
            $em->flush();
        }
        
        ($this->publisher)(new Update([
            "/usuarios/{$usuario->getId()}/fila",
        ], json_encode([ 'id' => $usuario->getId() ])));

        return $servicoUsuario;
    }

    public function updateServicoUsuario(Usuario $usuario, Servico $servico, Unidade $unidade, int $peso): ?ServicoUsuario
    {
        $em = $this->storage->getManager();

        $servicoUsuario = $em
            ->getRepository(ServicoUsuario::class)
            ->findOneBy([
                'usuario' => $usuario,
                'servico' => $servico,
                'unidade' => $unidade
            ]);
        
        if ($servicoUsuario && $peso > 0) {
            $servicoUsuario->setPeso($peso);
            $em->persist($servicoUsuario);
            $em->flush();
        }
        
        ($this->publisher)(new Update([
            "/usuarios/{$usuario->getId()}/fila",
        ], json_encode([ 'id' => $usuario->getId() ])));

        return $servicoUsuario;
    }
}
