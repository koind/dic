<?php

namespace Koind\Fortest;

class Data implements DataInterface
{
    private $data;

    public function add(string $value): void
    {
        $this->data = $value;
    }

    public function get(): string
    {
        return $this->data;
    }
}