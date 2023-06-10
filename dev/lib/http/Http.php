<?php

namespace lib\http;

use lib\App;

class Http
{
    protected App $app;
    protected string $libPath = '';

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->libPath = $app->getLibPath();
    }

    public function run()
    {
        $request = $this->app->make('request');
        try {
            $response = $this->runWithRequest($request);
        } catch (\Exception $e) {
            $response = $e;
        }
        return $response;
    }

    public function runWithRequest(Request $request)
    {
        return $this->dispatchToRoute($request);
    }

    public function dispatchToRoute(Request $request)
    {
        $withRoute = $this->app->config->get('route.with_route', true);
        try {
            return $this->app->route->dispatch($request, $withRoute);
        } catch (\Exception $e) {
            die($e);
        }
    }
}