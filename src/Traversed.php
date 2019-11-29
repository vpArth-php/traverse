<?php

namespace Arth\Util;

class Traversed extends Traverse
{
  public function offsetGet($offset)
  {
    $data = parent::offsetGet($offset);
    if (null === $data) {
      return null;
    }

    return new static($data, $this->separator);
  }
  public function getValue() { return $this->data; }
}
