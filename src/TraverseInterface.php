<?php

namespace Arth\Util;

interface TraverseInterface
{
  public function setSeparator(string $separator): void;
  public function has($path, $data): bool;
  public function getLink($path, &$data);
  public function get($path, $data);
  public function del($path, &$data): void;
  public function set($path, $value, &$data): void;
  public function getPath(string $key, $skipLast = 0): array;
}
