<?php

/*
 * This file is part of the Novo SGA project.
 *
 * (c) Rogerio Lino <rogeriolino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novosga\Http;

use Exception;

/**
 * Envelope
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class Envelope implements \JsonSerializable
{
    /**
     * @var bool
     */
    private $success;
    
    /**
     * @var string
     */
    private $sessionStatus;
    
    /**
     * @var mixed
     */
    private $data;
    
    /**
     * @var string
     */
    private $message;
    
    /**
     * @var string
     */
    private $detail;

    public function __construct()
    {
        $this->success = true;
        $this->sessionStatus = 'active';
    }
    
    public function isSuccess()
    {
        return $this->success;
    }

    public function getSessionStatus()
    {
        return $this->sessionStatus;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setSuccess($success)
    {
        $this->success = $success;
        return $this;
    }

    public function setSessionStatus($session)
    {
        $this->sessionStatus = $session;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
    
    public function getDetail()
    {
        return $this->detail;
    }

    public function setDetail($detail)
    {
        $this->detail = $detail;
        return $this;
    }
    
    public function exception(Exception $e, $debug = false)
    {
        $this
            ->setSuccess(false)
            ->setMessage($e->getMessage());
        
        if ($debug) {
            $detail = "{$e->getFile()}:{$e->getLine()}\n{$e->getTraceAsString()}";
            $this->setDetail($detail);
        }
        
        return $this;
    }
        
    public function jsonSerialize()
    {
        $body = [
            'success'        => $this->success,
            'sessionStatus'  => $this->sessionStatus,
            'time'           => time() * 1000,
        ];
        
        if ($this->success) {
            $body['data'] = $this->data;
        } else {
            $body['message'] = $this->message;
            if ($this->detail) {
                $body['detail'] = $this->detail;
            }
        }
        
        return $body;
    }
}