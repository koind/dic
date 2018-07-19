<?php

namespace Koind;

/**
 * Class Container
 *
 * @package Koind
 */
class Container
{
    /**
     * @var array Сontains definitions of anonymous functions
     * needed to create the required classes.
     */
    protected $definitions    = [];

    /**
     * @var array Contains instances of created objects.
     */
    protected $shared         = [];

    /**
     * Writes an anonymous function that returns an instance of the object.
     *
     * Each time you request a component, a new instance of the class will
     * be created.
     *
     * @param string $name
     * @param string|callable $value
     * @throws \InvalidArgumentException
     */
    public function set(string $name, $value): void
    {
        $this->doesItFunctionOrString($value);

        $this->shared[$name]      = null;
        $this->definitions[$name] = [
            'value'     => $value,
            'shared'    => false,
        ];
    }

    /**
     * Writes an anonymous function that returns an instance of the object if
     * it was not created earlier and adds it to the buffer.
     *
     * The component is created once and placed in the buffer at each request
     * to receive the component, it is taken from the buffer.
     *
     * @param string $name
     * @param string|callable $value
     * @throws \InvalidArgumentException
     */
    public function setShared(string $name, $value): void
    {
        $this->doesItFunctionOrString($value);

        $this->shared[$name]      = null;
        $this->definitions[$name] = [
            'value'     => $value,
            'shared'    => true,
        ];
    }

    /**
     * If the component is in the buffer returns from the buffer in the
     * remaining cases creates.
     *
     * @param string $name
     * @return mixed|object
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function get(string $name): object
    {
        if (isset($this->shared[$name])) {
            return $this->shared[$name];
        }

        if (array_key_exists($name, $this->definitions)) {
            $value  = $this->definitions[$name]['value'];
            $shared = $this->definitions[$name]['shared'];
        }

        if (is_string($value)) {
            $reflection = new \ReflectionClass($value);
            $arguments  = [];

            if (($construct = $reflection->getConstructor()) !== null) {
                foreach ($construct->getParameters() as $param) {
                    $paramClass     = $param->getClass();
                    $arguments[]    = $paramClass ? $this->get($paramClass->getName()) : null;
                }
            }

            $component = $reflection->newInstanceArgs($arguments);
        } else {
            $component = call_user_func($value, $this);
        }

        if (!$component) {
            throw new \Exception('Undefined component ' . $name);
        }

        if ($shared) {
            $this->shared[$name] = $component;
        }

        return $component;
    }

    /**
     * Throw an exception if the passed value is not a function or not a string.
     *
     * @param $value
     * @throws \InvalidArgumentException
     */
    protected function doesItFunctionOrString($value)
    {
        if (!is_callable($value) && !is_string($value)) {
            throw new \InvalidArgumentException("Incorrect type " . $value);
        }
    }
}