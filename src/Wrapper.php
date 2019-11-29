<?php

namespace Arth\Util\Traverse;

class Wrapper extends Container
{
  public function get($offset)
  {
    $data = parent::get($offset);
    if (null === $data) {
      return null;
    }

    return new static($data, $this->separator);
  }
  public function getValue() { return $this->data; }
}
