<?php

namespace lib\http;

use lib\component\Container;

abstract class Response
{
    protected $data;
    protected $contentType = 'text/html';
    protected $charset = 'utf-8';
    protected $code = 200;
    protected $header = [];

    public function init($data = '', $code = 200)
    {
        $this->data = $data;
        $this->code = $code;
        $this->contentType($this->contentType, $this->charset);
    }

    public function send()
    {
        http_response_code($this->code);
        foreach ($this->header as $name => $value) {
            header($name . ': ' . $value);
        }
        $this->sendData();
    }

    protected function contentType(mixed $contentType, mixed $charset)
    {
        $this->header['Content-Type'] = $contentType . '; charset=' . $charset;
        return $this;
    }

    public static function create($data = '', $type = 'html', $code = 200)
    {
        $class = str_contains($type, '\\') ? $type : '\\lib\\http\\response\\' . ucfirst(strtolower($type));
        return Container::getInstance()->invokeClass($class, ['data' => $data, 'code' => $code]);
    }

    public function end()
    {
        exit();
    }

    public function sendData()
    {
        if (is_string($this->data)) {
            echo $this->data;
        } else {
            echo json_encode($this->data);
        }
    }
}