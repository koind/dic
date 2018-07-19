<?php

namespace Tests;

class Data
{
    private $data;

    public function add($value)
    {
        $this->data = $value;
    }

    public function get()
    {
        return $this->data;
    }
}