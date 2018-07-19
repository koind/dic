<?php

namespace Koind\Tests;

use Koind\Container;
use Koind\Fortest\Data;
use Koind\Fortest\SessionStorage;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    private $container;

    public function setUp()
    {
        $this->container = new Container();
    }

    public function testSetData()
    {
        $this->container->set('Koind\Fortest\DataInterface', 'Koind\Fortest\Data');
        $this->assertEquals(
            new Data(),
            $data = $this->container->get('Koind\Fortest\DataInterface')
        );
        $data->add(12);
        $this->assertEquals(12, $data->get());
    }

    public function testSetStorage()
    {
        $this->container->set('Koind\Fortest\SessionStorage', function (Container $container) {
            return new SessionStorage('auth');
        });
        $this->assertEquals(
            new SessionStorage('auth'),
            $storage = $this->container->get('Koind\Fortest\SessionStorage')
        );
        $this->assertEquals('auth', $storage->getKey());
    }

    public function testSetSharedAuth()
    {
        $this->container->setShared('auth', 'Koind\Fortest\Auth');
        $auth = $this->container->get('auth');
        var_dump($auth);
        $this->assertEquals(true, $auth->login('ivan', 'pass123'));
    }
}