<?php

namespace Test;

use Arth\Util\Traverse as T;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
  public function testService(): void
  {
    $svc  = new T\Service(':');
    $data = json_decode('{"a": 1}', false);

    static::assertEquals('{"a":1}', json_encode($svc->get('', $data)));

    $svc->set('a:b:c', 15, $data);
    static::assertEquals('{"a":{"b":{"c":15}}}', json_encode($data));
    $svc->del('a:b', $data);
    $svc->set('a:x', null, $data);

    static::assertEquals('{"a":{"x":null}}', json_encode($data));

    static::assertFalse($svc->has('a:b', $data));
    static::assertFalse($svc->has('a:x:c', $data));
    static::assertTrue($svc->has('a:x', $data));

    static::assertEquals(42, $svc->get('a:1', ['a' => [24, 42]]));

    static::assertEquals(['a', 'b'], $svc->getPath('a:b:c:d', 2));

    static::assertNull($svc->get('a:42', ['a:42' => 'oops']));
    $svc->setSeparator('.');
    static::assertNull($svc->get('oops', ['a:42' => 'oops']));

    // is reference
    $data = json_decode('{"a": {"value": 1}}', true);
    $v    = &$svc->getLink('a.value', $data);
    $v    = 2;
    static::assertEquals(2, $v);
    static::assertEquals(2, $data['a']['value']);
  }
}
