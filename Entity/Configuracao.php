<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * TODO: delete!
 */

namespace Novosga\Entity;

use Doctrine\ORM\EntityManager;

/**
 * Configuracao
 */
class Configuracao implements \JsonSerializable
{
    const STRING  = 1;
    const NUMERIC = 2;
    const COMPLEX = 3;

    /**
     * @var string
     */
    private $chave;

    /**
     * @var string
     */
    private $valor;

    /**
     * @var int
     */
    private $tipo;

    // transient
    private $parsedValue;

    public function __construct($chave = '', $valor = '')
    {
        $this->setChave($chave);
        $this->setValor($valor);
    }

    public function getChave()
    {
        return $this->chave;
    }

    public function setChave($chave)
    {
        $this->chave = $chave;
    }

    public function getValor()
    {
        if (!$this->parsedValue) {
            $this->parsedValue = ($this->tipo == self::COMPLEX) ? unserialize($this->valor) : $this->valor;
        }

        return $this->parsedValue;
    }

    public function setValor($valor)
    {
        $this->parsedValue = $valor;
        $this->tipo = self::tipo($valor);
        $this->valor = ($this->tipo == self::COMPLEX) ? serialize($valor) : $valor;
    }

    public function __toString()
    {
        return $this->getChave().'='.$this->getValor();
    }

    private static function tipo($valor)
    {
        if (is_numeric($valor)) {
            return self::NUMERIC;
        } elseif (is_string($valor)) {
            return self::STRING;
        } else {
            return self::COMPLEX;
        }
    }

    /**
     * Retorna a configuração a partir da chave informada.
     *
     * @param type $key
     *
     * @return Novosga\Entity\Configuracao
     */
    public static function get(EntityManager $em, $key)
    {
        try {
            return $em
                ->createQuery("SELECT e FROM Novosga\Entity\Configuracao e WHERE e.chave = :key")
                ->setParameter('key', $key)
                ->getOneOrNullResult();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Cria ou atualiza uma configuração.
     *
     * @param string $key
     *
     * @return Novosga\Entity\Configuracao
     */
    public static function set(EntityManager $em, $key, $value)
    {
        try {
            $config = $em
                ->createQuery("SELECT e FROM Novosga\Entity\Configuracao e WHERE e.chave = :key")
                ->setParameter('key', $key)
                ->getSingleResult();
            
            $config->setValor($value);
            $em->merge($config);
            $em->flush();
        } catch (\Doctrine\ORM\NoResultException $e) {
            $config = new self($key, $value);
            $em->persist($config);
            $em->flush();
        }
    }

    /**
     * Apaga uma configuração.
     *
     * @param string $key
     *
     * @return bool
     */
    public static function del(EntityManager $em, $key)
    {
        return $em
            ->createQuery("DELETE FROM Novosga\Entity\Configuracao e WHERE e.chave = :key")
            ->setParameter('key', $key)
            ->execute();
    }

    public function jsonSerialize()
    {
        return [
            'chave' => $this->getChave(),
            'valor' => $this->getValor(),
        ];
    }
}
