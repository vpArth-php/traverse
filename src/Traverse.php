<?php

namespace Arth\Util\Traverse;

use Arth\Util\String\Escaper;
use StdClass;

class Traverse
{
  public static function &get($path, &$data, $separator = '.', $escape = '\\')
  {
    if ($path === '' || $path === []) {
      return $data;
    }
    $null = null;
    if (empty((array)$data)) {
      return $null;
    }
    if (is_string($path)) {
      $path = static::getPath($path, $separator, $escape);
    }
    $k = array_shift($path);

    if (is_array($data)) {
      $v = &$data[$k];
    } else {
      $v = &$data->$k;
    }

    return static::get($path, $v);
  }
  public static function del($path, &$data, $separator = '.', $escape = '\\'): void
  {
    $isArray = is_array($data);
    if ($path === '' || $path === []) {
      // Empty path interprets as clear
      $data = $isArray ? [] : new StdClass();
      return;
    }
    if (is_string($path)) {
      $path = static::getPath($path, $separator, $escape);
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
  public static function set($path, $value, &$data, $separator = '.', $escape = '\\'): void
  {
    if ($path === '' || $path === []) {
      $data = $value;
      return;
    }
    if (is_string($path)) {
      $path = static::getPath($path, $separator, $escape);
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
  public static function has($path, $data, $separator = '.', $escape = '\\'): bool
  {
    if ($path === '' || $path === []) {
      return false;
    }
    if (is_string($path)) {
      $path = static::getPath($path, $separator, $escape);
    }
    $k = array_shift($path);

    if (empty($path)) {
      return array_key_exists($k, (array)$data);
    }
    return static::has($path, is_array($data) ? $data[$k] : $data->$k);
  }
  public static function getPath(string $key, $separator = '.', string $escape = '\\', $skipLast = 0): array
  {
    $esc = new Escaper($escape);

    $path = $esc->split($separator, $key);

    return array_slice($path, 0, $skipLast ? -$skipLast : null);
  }
}
