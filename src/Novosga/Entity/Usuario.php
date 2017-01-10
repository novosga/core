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

use DateTime;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;

/**
 * Usuario
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class Usuario implements \JsonSerializable, AdvancedUserInterface, EncoderAwareInterface, \Serializable
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var string
     */
    private $login;

    /**
     * @var string
     */
    private $nome;

    /**
     * @var string
     */
    private $sobrenome;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $senha;

    /**
     * @var bool
     */
    private $status;

    /**
     * @var DateTime
     */
    private $ultimoAcesso;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $sessionId;

    /**
     * @var Lotacao[]
     */
    private $lotacoes;

    /**
     * @var Lotacao
     */
    private $lotacao;

    /**
     * @var bool
     */
    private $admin;

    /**
     * @var string
     */
    private $algorithm;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var array
     */
    private $roles = [];

    public function __construct()
    {
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setSobrenome($sobrenome)
    {
        $this->sobrenome = $sobrenome;
        return $this;
    }

    public function getSobrenome()
    {
        return $this->sobrenome;
    }

    /**
     * Retorna o nome completo do usuario (nome + sobrenome).
     *
     * @return string
     */
    public function getNomeCompleto()
    {
        return $this->nome . ' ' . $this->sobrenome;
    }
    
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
    
    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    
    public function getLotacao()
    {
        return $this->lotacao;
    }

    public function setLotacao(Lotacao $lotacao = null)
    {
        $this->lotacao = $lotacao;
        return $this;
    }
    
    public function getLotacoes()
    {
        return $this->lotacoes;
    }
    
    public function setSalt($salt) 
    {
        $this->salt = $salt;
        return $this;
    }

    public function setLotacoes($lotacoes)
    {
        $this->lotacoes = $lotacoes;
        return $this;
    }

    public function addLotacoe(Lotacao $lotacao)
    {
        $lotacao->setUsuario($this);
        $this->getLotacoes()->add($lotacao);
    }

    public function removeLotacoe(Lotacao $lotacao)
    {
        $this->getLotacoes()->removeElement($lotacao);
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getUltimoAcesso()
    {
        return $this->ultimoAcesso;
    }

    public function setUltimoAcesso($ultimoAcesso)
    {
        $this->ultimoAcesso = $ultimoAcesso;
        return $this;
    }
    
    public function getIp()
    {
        return $this->ip;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }

    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
        return $this;
    }

    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
        return $this;
    }
    
    public function isAdmin()
    {
        return $this->admin;
    }

    public function setAdmin($admin)
    {
        $this->admin = $admin;
        return $this;
    }
    
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return !!$this->getStatus();
    }

    public function eraseCredentials()
    {
    }

    public function getPassword()
    {
        return $this->getSenha();
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function addRole($role)
    {
        $this->roles[] = $role;
        return $this;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->getLogin();
    }

    public function getEncoderName()
    {
        return $this->algorithm;
    }

    public function serialize()
    {
        return serialize([
            $this->id,
            $this->login,
            $this->nome,
            $this->sessionId,
        ]);
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->login,
            $this->nome,
            $this->sessionId,
        ) = unserialize($serialized);
    }

    public function jsonSerialize()
    {
        return [
            'id'        => $this->getId(),
            'login'     => $this->getLogin(),
            'nome'      => $this->getNome(),
            'sobrenome' => $this->getSobrenome(),
            'status'    => $this->getStatus()
        ];
    }
    
    public function __tostring() {
        return $this->getLogin() . '';
    }

}
