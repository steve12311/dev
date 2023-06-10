<?php

namespace lib;

class Config
{
    protected array $config = [];
    protected string $path;

    public function __construct(App $app)
    {
        $this->path = $app->getConfigPath();
    }

    protected function put($fileName)
    {
        $this->config = require $this->path . $fileName . '.php';
    }

    public function get($name = null, $default = null)
    {
        if (is_null($name)) {
            return $this->config;
        }
        $name = explode('.', $name);
        $this->put($name[0]);
        $config = $this->config;
        return $config[$name[1]] ?? $default;
    }
}