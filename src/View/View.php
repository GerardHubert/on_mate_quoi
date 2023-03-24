<?php

declare(strict_types=1);

namespace App\View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class View
{
  private $twig;
  public function __construct()
  {
    $loader = new FilesystemLoader('../templates');
    $this->twig = new Environment($loader);
  }

  public function render(string $template, array $data)
  {
    echo $this->twig->render($template, $data);
  }
}
