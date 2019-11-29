<?php

namespace Arth\Util\Traverse;

use Countable;
use JsonSerializable;

class Container extends AbstractContainer implements Countable, JsonSerializable
{
  protected $data;
  protected $separator;
  public function __construct($data, $separator = '.')
  {
    $this->data      = $data;
    $this->separator = $separator;
  }

  protected function has($key): bool { return Traverse::has($key, $this->data, $this->separator); }
  protected function get($key) { return Traverse::get($key, $this->data, $this->separator); }
  protected function set($key, $value): void { Traverse::set($key, $value, $this->data, $this->separator); }
  protected function del($key): void { Traverse::del($key, $this->data, $this->separator); }

  public function count() { return count($this->data); }
  public function jsonSerialize(): array { return (array)$this->data; }
}
