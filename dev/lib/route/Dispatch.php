<?php

namespace lib\route;

use lib\App;
use lib\http\Request;

abstract class Dispatch
{
    protected App $app;
    protected Request $request;
    protected string $url;

    public function __construct(Request $request, $url)
    {
        $this->request = $request;
        $this->url = $url;
    }

    public function init(App $app)
    {
        $this->app = $app;
    }
}