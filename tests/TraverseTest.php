<?php

namespace Test;

use Arth\Util\Traverse;
use Arth\Util\Traversed;
use Arth\Util\TraverseService;
use PHPUnit\Framework\TestCase;

class TraverseTest extends TestCase
{
  public function testInstance(): void
  {
    $data = json_decode('{"a": 1}', false);
    $svc  = new Traverse($data, ':');

    static::assertEquals('{"a":1}', json_encode($svc));

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
  public function testTraversed(): void
  {
    $data = json_decode('{"a": 1}', false);
    $svc  = new Traversed($data, ':');

    static::assertEquals('{"a":1}', json_encode($svc));

    $svc->{'a:b:c'} = 15;
    static::assertEquals('{"a":{"b":{"c":15}}}', json_encode($data));

    /** @var Traversed $x */
    $x = $svc->{''};
    static::assertInstanceOf(Traversed::class, $x);
    static::assertEquals('{"a":{"b":{"c":15}}}', json_encode($x->getValue()));

    unset($svc->{'a:b'});
    $svc['a:x'] = null;
    static::assertEquals('{"a":{"x":null}}', json_encode($data));
    static::assertTrue(isset($svc->{'a'}));
    static::assertFalse(isset($svc->{'a:x'}));
    static::assertArrayHasKey('a:x', $svc);
    static::assertArrayNotHasKey('b', $svc);
  }
  public function testService(): void
  {
    $svc  = new TraverseService(':');
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
