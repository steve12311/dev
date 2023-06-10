<?php

namespace lib\component;


use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use lib\exception\ContainerException;
use lib\exception\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use Traversable;

class Container implements ArrayAccess, IteratorAggregate, Countable, ContainerInterface
{
    protected static $instance;
    protected array $instances = [];
    protected array $bind = [];
    protected array $invokeCallback = [];


    /**
     * @inheritDoc
     */
    public function get(string $id)
    {
        // TODO: Implement get() method.
        if ($this->has($id)) {
            return $this->make($id);
            //return $this->instances[$id];
        }
        throw new NotFoundException('class not exists: ' . $id);
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        // TODO: Implement has() method.
        return isset($this->instances[$id]) || isset($this->bind[$id]);
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        // TODO: Implement getIterator() method.
        return new ArrayIterator($this->instances);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        // TODO: Implement offsetExists() method.
        return $this->bind[$offset] ?? false;
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        // TODO: Implement offsetGet() method.
        return $this->bind[$offset] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        // TODO: Implement offsetSet() method.
        $this->bind[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        // TODO: Implement offsetUnset() method.
        unset($this->bind[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        // TODO: Implement count() method.
        return count($this->bind);
    }

    /**
     * @inheritDoc
     */
    public function __get(string $name)
    {
        // TODO: Implement __get() method.
        if ($this->has($name)) {
            return $this->get($name);
        }
        $this->instances[$name] = $this->make($name);
        return $this->get($name);
    }

    /**
     * @inheritDoc
     */
    public function __set(string $name, $value): void
    {
        // TODO: Implement __set() method.
    }

    public function bind($id, $value = null)
    {
        if (is_array($id)) {
            $this->bind = array_merge($this->bind, $id);
        } else {
            $this->bind[$id] = $value;
        }
    }

    public function make(string $id, array $parameters = [])
    {
        $id = $this->bind[$id] ?? $id;
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }
        $object = $this->invokeClass($id, $parameters);
        $this->instances[$this->getAlias($id)] = $object;
        return $object;
    }

    public function invokeClass(string $id, array $parameters = [])
    {
        try {
            $reflector = new ReflectionClass($id);
        } catch (ReflectionException $e) {
            throw new ContainerException('class not exists: ' . $id, $e->getCode(), $e);
        }
        if (!$reflector->isInstantiable()) {
            throw new ContainerException('class not instantiable: ' . $id);
        }
        $constructor = $reflector->getConstructor();
        $args = $constructor ? $this->bindParams($constructor, $parameters) : [];
        return $reflector->newInstanceArgs($args);
    }

    public function bindParams($constructor, $parameters)
    {
        $args = [];
        foreach ($constructor->getParameters() as $parameter) {
            $name = $parameter->getName();
            if (isset($parameters[$name])) {
                $args[] = $parameters[$name];
            } else if ($parameter->isDefaultValueAvailable()) {
                $args[] = $parameter->getDefaultValue();
            } else if ($parameter->getType()) {
                $args[] = $this->make($parameter->getType()->getName());
            } else {
                throw new ContainerException('Unable to resolve dependency: ' . $name);
            }
        }
        return $args;
    }

    public function getAlias(string $abstract)
    {
        return array_search($abstract, $this->bind, true) ?: $abstract;
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public static function setInstance($instance)
    {
        static::$instance = $instance;
    }
}