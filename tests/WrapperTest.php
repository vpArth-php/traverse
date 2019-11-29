<?php

namespace Test;

use Arth\Util\Traverse as T;
use PHPUnit\Framework\TestCase;

class WrapperTest extends TestCase
{
  public function testWrapper(): void
  {
    $data = json_decode('{"a": 1}', false);
    $svc  = new T\Wrapper($data, ':');

    static::assertEquals('{"a":1}', json_encode($svc));

    $svc->{'a:b:c'} = 15;
    static::assertEquals('{"a":{"b":{"c":15}}}', json_encode($data));

    /** @var T\Wrapper $x */
    $x = $svc->{''};
    static::assertInstanceOf(T\Wrapper::class, $x);
    static::assertEquals('{"a":{"b":{"c":15}}}', json_encode($x->getValue()));

    unset($svc->{'a:b'});
    $svc['a:x'] = null;
    static::assertEquals('{"a":{"x":null}}', json_encode($data));
    static::assertTrue(isset($svc->{'a'}));
    static::assertFalse(isset($svc->{'a:x'}));
    static::assertArrayHasKey('a:x', $svc);
    static::assertArrayNotHasKey('b', $svc);
  }
}
