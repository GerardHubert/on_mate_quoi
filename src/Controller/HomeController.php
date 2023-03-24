<?php

declare(strict_types=1);

namespace App\Controller;

use App\View\View;

class HomeController
{
  private $view;
  public function __construct()
  {
    $this->view = new View();
  }

  public function home()
  {
    $this->view->render('base.html.twig', []);
  }
}
