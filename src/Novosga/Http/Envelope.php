<?php

namespace Novosga\Http;

/**
 * Envelope
 *
 * @author Rogerio Lino <rogeriolino@gmail.com>
 */
class Envelope implements \JsonSerializable
{
    
    private $body;

    public function __construct($data = null, $success = true)
    {
        $this->body = [
            'success'  => $success,
            'invalid'  => false,
            'inactive' => false,
            'time'     => time() * 1000
        ];
        
        if ($success) {
            $this->body['data'] = $data;
        } else {
            $this->body['message']  = $data;
        }
        
        parent::__construct($this->body);
    }
    
    public function jsonSerialize()
    {
        return $this->body;
    }

}
