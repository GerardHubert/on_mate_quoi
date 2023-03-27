<?php

declare(strict_types=1);

namespace App\Services;

use \PDO;

final class Database extends PDO
{
  private $dsn;
  private $username;
  private $password;

  public function __construct()
  {
    $this->dsn = $_ENV["DB_DSN"];
    $this->username = $_ENV['DB_USERNAME'];
    $this->password = $_ENV['DB_PASSWORD'];
    parent::__construct($this->dsn, $this->username, $this->password);
  }
}
