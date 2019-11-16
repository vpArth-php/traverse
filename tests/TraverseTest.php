<?php

namespace Test;

use Arth\Util\Traverse;
use PHPUnit\Framework\TestCase;

class TraverseTest extends TestCase
{
  public function testInstance(): void
  {
    $data = json_decode('{"a": 1}');
    $svc  = new Traverse($data, ':');

    $svc->{'a:b:c'} = 15;
    static::assertEquals('{"a":{"b":{"c":15}}}', json_encode($data));
    static::assertEquals('{"a":{"b":{"c":15}}}', json_encode($svc->{''}));
    unset($svc->{'a:b'});
    $svc['a:x'] = null;
    static::assertEquals('{"a":{"x":null}}', json_encode($data));
    static::assertTrue(isset($svc->{'a'}));
    static::assertFalse(isset($svc->{'a:x'}));
    static::assertArrayHasKey('a:x', $svc);
    static::assertArrayNotHasKey('b', $svc);
  }
}