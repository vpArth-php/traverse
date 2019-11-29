<?php

namespace Arth\Util\Traverse;

class Wrapper extends Container
{
  /** @return Wrapper|null */
  public function get($offset)
  {
    $data = parent::get($offset);
    if (null === $data) {
      return null;
    }

    return new static($data, $this->separator, $this->escape);
  }
  public function getValue() { return $this->data; }
}
