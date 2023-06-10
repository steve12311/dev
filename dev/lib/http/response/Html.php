<?php

namespace lib\http\response;

use lib\http\Response;

class Html extends Response
{
    protected $contentType = 'text/html';

    public function __construct($data = '', $code = 200)
    {
        $this->init($data, $code);
    }
}