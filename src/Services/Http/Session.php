<?php

declare(strict_types=1);

namespace App\Services\Http;

class Session
{
  private $session;
  public function __construct()
  {
    return session_start();
  }

  public function getSession(): ?array
  {
    return !empty($_SESSION) ? $_SESSION : null;
  }

  public function add($key, $value)
  {
    $_SESSION[$key] = $value;
  }
}
