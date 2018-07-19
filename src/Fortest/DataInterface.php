<?php

namespace Koind\Fortest;

interface DataInterface
{
    public function add(string $value): void;
    public function get(): string;
}