<?php

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
    
    public function exception(Exception $e)
    {
        $this
            ->setSuccess(false)
            ->setMessage($e->getMessage());
        
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
            $body['message']  = $this->message;
        }
        
        return $body;
    }
}