<?php

namespace lib\route;

use lib\http\Request;
use lib\http\Response;

class Url extends Dispatch
{
    protected array $routes = [];

    public function __construct(Request $request, array $routes, $url)
    {
        parent::__construct($request, $url);
        $this->routes = $routes;
    }

    protected function parseUrl()
    {
        $url = $this->url;
        $method = $this->request->getMethod();
        if (!empty($this->routes[$method])) {
            $flag = false;
            foreach ($this->routes[$method] as $route => $action) {
                if (preg_match("#^$route$#", $url)) {
                    $url = $action;
                    $flag = true;
                    break;
                }
            }
            if (!$flag) {
                throw new \Exception("Route not found");
            }
        } else {
            throw new \Exception("Method not found");
        }
        $match = array_filter(explode('/', $url));
        if (empty($match)) {
            $action = 'index';
        } else {
            $action = array_pop($match);
        }
        if (empty($match)) {
            $controller = 'index';
        } else {
            $controller = array_pop($match);
        }
        return [$controller, $action];
    }

    protected function exec()
    {
        try {
            [$controller, $action] = $this->parseUrl();
        } catch (\Exception $e) {
            die($e);
        }
        $controller = $this->app->make("app\\controller\\" . ucfirst($controller));
        $reflector = new \ReflectionClass($controller);
        if (!$reflector->hasMethod($action)) {
            throw new \Exception("Action not found");
        }
        $method = $reflector->getMethod($action);
        $parameters = $method->getParameters();
        $methodParameters = [];
        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            if (empty($this->request->getQuery($name)) && empty($this->request->postQuery($name))) {
                if ($parameter->isDefaultValueAvailable()) {
                    $methodParameters[] = $parameter->getDefaultValue();
                    continue;
                }
                throw new \Exception("Parameter $name not found");
            }
            $methodParameters[] = $this->request->getQuery($name) ?? $this->request->postQuery($name);
        }
        return $method->invokeArgs($controller, $methodParameters);
    }

    public function run()
    {
        $data = $this->exec();
        if (empty($this->request->getHeader("User-Agent"))) {
            return Response::create($data, 'json');
        }
        return Response::create($data);
    }
}