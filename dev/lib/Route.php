<?php

namespace lib;

use lib\http\Request;
use lib\route\Url;

class Route
{
    protected static $routes = [];
    protected $request;

    protected App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        if (is_file($this->app->getAppPath() . "Router.php")) {
            require_once $this->app->getAppPath() . "Router.php";
        }
        $this->sort();
    }

    protected function sort()
    {
        $this->sortRoutes('GET');
        $this->sortRoutes('POST');
        $this->sortRoutes('PUT');
        $this->sortRoutes('DELETE');
    }

    public function dispatch(Request $request, $withRoute = null)
    {
        $this->request = $request;
        if ($withRoute) {
            $dispatch = $this->url($request->getUrl());
        } else {
            throw new \Exception("Route not found");
        }
        $dispatch->init($this->app->getInstance());
        return $dispatch->run();
    }

    public function url($url)
    {
        return new Url($this->request, self::$routes, $url);
    }

    public static function get($url, $action)
    {
        self::$routes['GET'][$url] = $action;
    }

    public static function post($url, $action)
    {
        self::$routes['POST'][$url] = $action;
    }

    public static function put($url, $action)
    {
        self::$routes['PUT'][$url] = $action;
    }

    public static function delete($url, $action)
    {
        self::$routes['DELETE'][$url] = $action;
    }

    protected function sortRoutes($key)
    {
        if (isset(self::$routes[$key])) {
            uksort(self::$routes[$key], [$this, 'customSort']);
        }
    }

    protected function customSort($a, $b)
    {
        $aParts = explode('/', $a);
        $bParts = explode('/', $b);
        $minCount = min(count($aParts), count($bParts));
        for ($i = 0; $i < $minCount; $i++) {
            $aPart = $aParts[$i];
            $bPart = $bParts[$i];
            if ($aPart !== $bPart) {
                return strcmp($bPart, $aPart);
            }
        }
        return count($bParts) - count($aParts);
    }
}