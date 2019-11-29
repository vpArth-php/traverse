<?php

namespace Test;

use Arth\Util\Traverse\Traverse;
use Generator;
use PHPUnit\Framework\TestCase;

class StaticTest extends TestCase
{
  /**
   * @dataProvider dataSet
   */
  public function testSet($subj, $path, $value, $expected, $separator = '.'): void
  {
    $obj = json_decode($subj, false);
    $arr = json_decode($subj, true);

    Traverse::set($path, $value, $obj, $separator);
    Traverse::set($path, $value, $arr, $separator);

    static::assertEquals($expected, json_encode($obj));
    static::assertEquals($expected, json_encode($arr));
  }

  /** @dataProvider dataGet */
  public function testGet($subj, $path, $expected, $separator = '.'): void
  {
    $obj = json_decode($subj, false);
    $arr = json_decode($subj, true);

    static::assertEquals($expected, json_encode(Traverse::get($path, $obj, $separator)));
    static::assertEquals($expected, json_encode(Traverse::get($path, $arr, $separator)));
  }
  public function testGetRef(): void
  {
    $subj = json_decode('{"a": {"b": {"c": {"n": null}}}}');
    $c    = Traverse::get('a.b.c', $subj);
    static::assertNull($subj->a->b->c->n);
    $c->n = 'Changed';
    static::assertEquals('Changed', $subj->a->b->c->n);
  }

  /** @dataProvider dataDel */
  public function testDel($subj, $path, $expected, $separator = '.'): void
  {
    $obj = json_decode($subj, false);
    $arr = json_decode($subj, true);

    $expectedObj = json_decode($expected, false);
    $expectedArr = json_decode($expected, true);

    Traverse::del($path, $obj, $separator);
    Traverse::del($path, $arr, $separator);

    static::assertEquals($expectedObj, $obj, json_encode(['o', $obj, $expectedObj]));
    static::assertEquals($expectedArr, $arr, json_encode(['a', $arr, $expectedArr]));
  }
  public function testHas(): void
  {
    $subj = json_decode('{"a": {"b": {"c": {"n": null}}}}');

    static::assertFalse(Traverse::has('', $subj));
    static::assertTrue(Traverse::has('a.b.c', $subj));
    static::assertTrue(Traverse::has('a.b.c.n', $subj));
    static::assertFalse(Traverse::has('x', $subj));
    static::assertFalse(Traverse::has('a.b.c.d', $subj));
  }

  public function testGetPath(): void
  {
    static::assertEquals(['a', 'b', 'c'], Traverse::getPath('a/b/c', '/', 0));
    static::assertEquals(['a', 'b', 'c'], Traverse::getPath('a/b/c/n', '/', 1));
    static::assertEquals(['a', 'b'], Traverse::getPath('a/b/c/n', '/', 2));
  }

  public function dataSet(): Generator
  {
    yield ['{}', 'a', 42, '{"a":42}'];
    yield ['{"a":21}', 'a', 42, '{"a":42}'];
    yield ['{"a":21}', 'a.b', 'replace scalar', '{"a":{"b":"replace scalar"}}'];
    yield ['{"a":{"b":{"c":[1]}}}', 'a.k.l.m', 7, '{"a":{"b":{"c":[1]},"k":{"l":{"m":7}}}}'];
    yield ['{"a":{"b":{"c":[1]}}}', 'x.k.l.m', 7, '{"a":{"b":{"c":[1]}},"x":{"k":{"l":{"m":7}}}}'];
    yield ['{}', 'a->2->b', 42, '{"a":{"2":{"b":42}}}', '->'];
  }
  public function dataGet(): Generator
  {
    yield ['{}', 'a.b.c.d', 'null'];
    yield ['{"a":{"b":42}}', 'a.b', '42'];
    yield ['{"a":{"b":42}}', 'a->b', '42', '->'];
    yield ['{"a":{"b":42}}', 'a->c', 'null', '->'];
    yield ['{"a":{"b":["x", 42]}}', 'a->b->1', '42', '->'];
    yield ['{"a":{"b":["x", 42]}}', 'a.b', '["x",42]'];
    yield ['{"a":{"b":["x", 42]}}', 'a/b/1', '42', '/'];
  }
  public function dataDel(): Generator
  {
    yield ['{"a":12}', '', '{}'];
    yield ['{}', 'a.b', '{}'];
    yield ['{"x":"X"}', 'a.b', '{"x":"X"}'];
    yield ['{"a":12,"b":34}', 'b', '{"a":12}'];
    yield ['{"a":12,"b":{"c": "b:c"}}', 'b.c', '{"a":12,"b":{}}'];
    yield ['{"a":12,"c":2}', 'd.c', '{"a":12,"c":2}'];
  }
}
