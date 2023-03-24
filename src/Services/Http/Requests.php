<?php

declare(strict_types=1);

namespace App\Services\Http;

class Requests
{
  private $get;
  public function __construct()
  {
    $this->get = $_GET;
  }

  public function get(): array
  {
    return $this->get;
  }
}
