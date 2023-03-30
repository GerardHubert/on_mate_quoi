<?php

declare(strict_types=1);

namespace App\Services\Http;

class Requests
{
  private $get;
  private $post;
  private $server;

  public function __construct()
  {
    $this->get = $_GET;
    $this->post = $_POST;
    $this->server = $_SERVER;
  }

  public function get(): array
  {
    return filter_var_array($this->get);
  }

  public function post(): array
  {
    return filter_var_array($this->post);
  }

  public function server(): array
  {
    return $this->server;
  }
}
