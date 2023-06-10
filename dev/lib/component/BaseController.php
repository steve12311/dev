<?php

namespace lib\component;

use lib\App;

class BaseController
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }
}