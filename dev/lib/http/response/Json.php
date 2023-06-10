<?php

namespace lib\http\response;

use lib\http\Response;

class Json extends Response
{
    protected $contentType = 'application/json';

    public function __construct($data = '', $code = 200)
    {
        $this->init($data, $code);
    }
}