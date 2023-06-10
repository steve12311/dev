<?php

namespace lib\exception;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;
use Throwable;

class ContainerException extends RuntimeException implements ContainerExceptionInterface
{
    protected $class;

    public function __construct(string $message, string $class = '', Throwable $previous = null)
    {
        $this->message = $message;
        $this->class = $class;

        parent::__construct($message, 0, $previous);
    }

    public function getClass()
    {
        return $this->class;
    }
}