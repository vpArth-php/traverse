<?php

namespace Arth\Util;

use ArrayAccess;
use Countable;
use JsonSerializable;
use StdClass;

class Traverse implements ArrayAccess, Countable, JsonSerializable
{
  protected $data;
  protected $separator;
  public function __construct($data, $separator = '.')
  {
    $this->data      = $data;
    $this->separator = $separator;
  }

  public function offsetExists($offset): bool { return static::has($offset, $this->data, $this->separator); }
  public function offsetGet($offset) { return static::get($offset, $this->data, $this->separator); }
  public function offsetSet($offset, $value): void { static::set($offset, $value, $this->data, $this->separator); }
  public function offsetUnset($offset): void { static::del($offset, $this->data, $this->separator); }

  public function __isset($name) { return null !== ($this[$name] ?? null); }
  public function __get($name) { return $this[$name]; }
  public function __set($name, $value) { $this[$name] = $value; }
  public function __unset($name) { unset($this[$name]); }

  public function count() { return count($this->data); }
  public function jsonSerialize() { return (array)$this->data; }

  public static function has($path, $data, $separator = '.'): bool
  {
    if ($path === '' || $path === []) {
      return false;
    }
    if (is_string($path)) {
      $path = static::getPath($path, $separator);
    }
    $k = array_shift($path);

    if (empty($path)) {
      return array_key_exists($k, (array)$data);
    }
    return static::has($path, is_array($data) ? $data[$k] : $data->$k);
  }
  public static function &get($path, &$data, $separator = '.')
  {
    if ($path === '' || $path === []) {
      return $data;
    }
    $null = null;
    if (empty((array)$data)) {
      return $null;
    }
    if (is_string($path)) {
      $path = static::getPath($path, $separator);
    }
    $k = array_shift($path);

    if (is_array($data)) {
      $v = &$data[$k];
    } else {
      $v = &$data->$k;
    }

    return static::get($path, $v);
  }
  public static function del($path, &$data, $separator = '.'): void
  {
    $isArray = is_array($data);
    if ($path === '' || $path === []) {
      // Empty path interprets as clear
      $data = $isArray ? [] : new StdClass();
      return;
    }
    if (is_string($path)) {
      $path = static::getPath($path, $separator);
    }
    $last = array_pop($path);
    $subj =& $data;
    foreach ($path as $k) {
      if ($isArray && !empty($data[$k])) {
        $subj =& $data[$k];
      } else if (!empty($data->$k)) {
        $subj =& $data->$k;
      } else {
        return;
      }
    }
    if ($isArray) {
      unset($subj[$last]);
    } else {
      unset($subj->$last);
    }
  }
  public static function set($path, $value, &$data, $separator = '.'): void
  {
    if ($path === '' || $path === []) {
      $data = $value;
      return;
    }
    if (is_string($path)) {
      $path = static::getPath($path, $separator);
    }
    $k = array_shift($path);

    $p = &$data;
    if (is_array($p)) {
      $v = $p[$k] ?? null;
      if (is_scalar($v) || $v === null) {
        $p[$k] = [];
      }

      $p = &$p[$k];
    } else {
      $v = $p->$k ?? null;
      if (is_scalar($v) || $v === null) {
        $p->$k = new StdClass();
      }

      $p = &$p->$k;
    }
    static::set($path, $value, $p);
  }
  public static function getPath(string $key, $separator = '.', $skipLast = 0)
  {
    $path = explode($separator, $key);

    for ($i = 0; $i < $skipLast; ++$i) {
      array_pop($path);
    }

    return $path;
  }

}
