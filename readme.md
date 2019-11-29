PHP Traverse service
====================

Service for get array/object deep access by string path with some separator

### Installation
```sh
composer req arth/traverse
```

### Usage

```php
use Arth\Util\Traverse as T;

$data = ['a' => ['b' => ['c' => 'Hello', 'd' => null]]];

$svc = new T\Service('->');

$svc->has('a->b->c', $data); // true
$svc->get('a->b->c', $data); // 'Hello'

$container = new T\Container($data);
$container['a.b']; // ['c' => 'Hello']
isset($container['a.b.d']); // false

$w = new T\Wrapper($data);
$w['a']['b.c']->getValue(); // 'Hello'
```

See tests for more examples
