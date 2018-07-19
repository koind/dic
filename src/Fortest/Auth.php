<?php

namespace Koind\Fortest;

class Auth
{
    private $data;
    private $session;

    public function __construct(DataInterface $data, SessionStorage $session)
    {
        $this->data = $data;
        $this->session = $session;
    }

    public function login(string $login, string $password): bool
    {
        if (('ivan' === $login) && ('pass123' === $password)) {
            return true;
        }

        return false;
    }
}