<?php

namespace lib;

use lib\component\Container;
use lib\http\Http;
use lib\http\Request;
use lib\http\Response;

/**
 * @property Http $http
 * @property Route $route
 * @property Config $config
 */
class App extends Container
{
    protected string $libPath = __DIR__ . DIRECTORY_SEPARATOR;
    protected string $appPath = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "app" . DIRECTORY_SEPARATOR;
    protected string $publicPath = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR;
    protected array $bind = [
        'http' => Http::class,
        'request' => Request::class,
        'response' => Response::class,
        'route' => Route::class,
        'config' => Config::class,
    ];

    public function __construct()
    {
        if (is_file($this->appPath . "provider.php")) {
            $this->bind = array_merge($this->bind, require $this->appPath . "provider.php");
        }
        static::setInstance($this);
    }

    public function getLibPath(): string
    {
        return $this->libPath;
    }

    public function getAppPath(): string
    {
        return $this->appPath;
    }

    public function getPublicPath(): string
    {
        return $this->publicPath;
    }

    public function getConfigPath()
    {
        return $this->appPath . "config" . DIRECTORY_SEPARATOR;
    }
}