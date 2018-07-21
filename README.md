# DIC - Dependency Injection Container
A very simple and convenient lib for working with Dependency Injection Container for PHP

## Installation

Run the following command from you terminal:


 ```bash
 composer require "koind/dic: ^1.0"
 ```

or add this to require section in your composer.json file:

 ```
 "koind/dic": "^1.0"
 ```

then run ```composer update```


## Usage

As an example, we will use the library ```multi-basket```, to start we install it:

 ```
 composer require "koind/multi-basket: ^1.0"
 ```

Now the implementation itself:

```php
<?php 

use Koind\Container;
use koind\storage\SessionStorage;

require __DIR__ . '/vendor/autoload.php';

// bootstrap
$container = new Container();
$container->set('koind\storage\StorageInterface', function (Container $container) {
   return new SessionStorage('cart');
});
$container->set('koind\cost\CalculatorInterface', 'koind\cost\SimpleCost');
$container->setShared('cart', 'koind\Cart');

// implementation logic
$cart = $container->get('cart');
$cart->add(5, 6, 100);

```

## Available Methods

The following methods are available:

```php
public function set(string $name, $value): void
public function setShared(string $name, $value): void
public function get(string $name): object
```

### Example usage

##### Method ```set()``` 

Writes an anonymous function that returns an instance of the object.

```php
$container->set('koind\storage\StorageInterface', function (Container $container) {
   return new SessionStorage('cart');
});

// Get component SessionStorage by name:
$container->get('koind\storage\StorageInterface');
```

You can also specify the interface and class that implements this interface:

```php
$container->set('koind\cost\CalculatorInterface', 'koind\cost\SimpleCost');

// Get component SimpleCost by name:
$container->get('koind\storage\CalculatorInterface');
```

Note: when you add a component through the ```set()``` method, each time this  component is called by the ```get()``` method, a new instance of this component will be created.

##### Method ```setShared()``` 

Writes an anonymous function that returns an instance of the object. The component is created once and placed in the buffer at each request to receive the component, it is taken from the buffer.

```php
$container->setShared('koind\storage\StorageInterface', function (Container $container) {
   return new SessionStorage('cart');
});

// Get component SessionStorage by name:
$container->get('koind\storage\StorageInterface');
```

You can also specify the interface and class that implements this interface:

```php
$container->setShared('koind\cost\CalculatorInterface', 'koind\cost\SimpleCost');

// Get component SimpleCost by name:
$container->get('koind\storage\CalculatorInterface');
```

Note. When you add a component through the ```setShared()``` method, each time this component is called by the ```get()``` method, only once is created, placed in the buffer, and returned. Subsequent requests for get the component are returned from the buffer.

##### Method ```get()```

If the component is in the buffer returns from the buffer in the remaining cases creates.

```php
$container->get('koind\storage\CalculatorInterface');
```

## Tests

Run the following command from you terminal:

```
phpunit
```