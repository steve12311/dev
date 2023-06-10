<?php

namespace app\controller;

use lib\component\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return "Hello World";
    }

    public function hello($name = 'my')
    {
        return 'hello,' . $name;
    }
}