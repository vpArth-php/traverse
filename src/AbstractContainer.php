<?php

namespace Arth\Util\Traverse;

use ArrayAccess;

abstract class AbstractContainer implements ArrayAccess
{
  public function offsetExists($key): bool { return $this->has($key); }
  public function offsetGet($key) { return $this->get($key); }
  public function offsetSet($key, $value): void { $this->set($key, $value); }
  public function offsetUnset($key): void { $this->del($key); }

  public function __isset($key): bool { return null !== $this->get($key); }
  public function __get($key) { return $this->get($key); }
  public function __set($key, $value): void { $this->set($key, $value); }
  public function __unset($key): void { $this->del($key); }

  abstract protected function has($key): bool;
  abstract protected function get($key);
  abstract protected function set($key, $value): void;
  abstract protected function del($key): void;
}
