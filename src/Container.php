<?php

namespace Arth\Util\Traverse;

use Countable;
use JsonSerializable;

class Container extends AbstractContainer implements Countable, JsonSerializable
{
  protected $data;
  /** @var string */
  protected $separator;
  /** @var string */
  protected $escape;
  public function __construct($data, string $separator = '.', string $escape = '\\')
  {
    $this->data      = $data;
    $this->separator = $separator;
    $this->escape    = $escape;
  }

  protected function has($key): bool
  {
    return Traverse::has($key, $this->data, $this->separator, $this->escape);
  }
  protected function get($key)
  {
    return Traverse::get($key, $this->data, $this->separator, $this->escape);
  }
  protected function set($key, $value): void
  {
    Traverse::set($key, $value, $this->data, $this->separator, $this->escape);
  }
  protected function del($key): void
  {
    Traverse::del($key, $this->data, $this->separator, $this->escape);
  }

  public function count() { return count($this->data); }
  public function jsonSerialize(): array { return (array)$this->data; }
}
