<?php

namespace lib\http;

class Request
{
    public function getMethod()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        }
        return 'GET';
    }

    public function getHeader($name, $default = null)
    {
        $name = strtoupper($name);
        $name = str_replace('-', '_', $name);
        $name = 'HTTP_' . $name;
        return $_SERVER[$name] ?? $default;
    }

    public function getHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $key = substr($key, 5);
                $key = str_replace('_', '-', $key);
                $key = strtolower($key);
                $headers[$key] = $value;
            }
        }
        return $headers;
    }

    public function getUri()
    {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }

    public function getUrl()
    {
        $uri = $this->getUri();
        return strtok($uri, '?');
    }

    public function getBody()
    {
        return file_get_contents('php://input');
    }

    public function getQuery($name = null, $default = null)
    {
        if (is_null($name)) {
            return $this->getQueryParams();
        }
        return $this->getQueryParam($name, $default);
    }

    private function getQueryParams()
    {
        $query = [];
        foreach ($_GET as $key => $value) {
            $query[$key] = $value;
        }
        return $query;
    }

    private function getQueryParam(mixed $name, mixed $default)
    {
        return $_GET[$name] ?? $default;
    }

    public function postQuery($name = null, $default = null)
    {
        if (is_null($name)) {
            return $this->getPostParams();
        }
        return $this->getPostParam($name, $default);
    }

    private function getPostParams()
    {
        $query = [];
        foreach ($_POST as $key => $value) {
            $query[$key] = $value;
        }
        return $query;
    }

    private function getPostParam(mixed $name, mixed $default)
    {
        return $_POST[$name] ?? $default;
    }
}