<?php

namespace Arth\Util\Traverse;

class Service implements TraverseInterface
{
  protected $separator;
  /** * @var string */
  protected $escape;

  public function __construct($separator = '.', $escape = '\\')
  {
    $this->setSeparator($separator);
    $this->setEscape($escape);
  }

  public function setSeparator(string $separator): void { $this->separator = $separator; }
  public function setEscape(string $escape): void { $this->escape = $escape; }

  public function has($path, $data): bool
  {
    return Traverse::has($path, $data, $this->separator, $this->escape);
  }
  public function &getLink($path, &$data)
  {
    return Traverse::get($path, $data, $this->separator, $this->escape);
  }
  public function get($path, $data)
  {
    return $this->getLink($path, $data);
  }
  public function del($path, &$data): void
  {
    Traverse::del($path, $data, $this->separator, $this->escape);
  }
  public function set($path, $value, &$data): void
  {
    Traverse::set($path, $value, $data, $this->separator, $this->escape);
  }
  public function getPath(string $key, $skipLast = 0): array
  {
    return Traverse::getPath($key, $this->separator, $this->escape, $skipLast);
  }
}
