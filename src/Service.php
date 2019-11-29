<?php

namespace Arth\Util\Traverse;

class Service implements TraverseInterface
{
  protected $separator;

  public function __construct($separator = '.') { $this->setSeparator($separator); }

  public function setSeparator(string $separator): void { $this->separator = $separator; }

  public function has($path, $data): bool
  {
    return Traverse::has($path, $data, $this->separator);
  }
  public function &getLink($path, &$data)
  {
    return Traverse::get($path, $data, $this->separator);
  }
  public function get($path, $data)
  {
    return $this->getLink($path, $data);
  }
  public function del($path, &$data): void
  {
    Traverse::del($path, $data, $this->separator);
  }
  public function set($path, $value, &$data): void
  {
    Traverse::set($path, $value, $data, $this->separator);
  }
  public function getPath(string $key, $skipLast = 0): array
  {
    return Traverse::getPath($key, $this->separator, $skipLast);
  }
}
