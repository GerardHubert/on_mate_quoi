<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Http\Requests;
use App\Controller\HomeController;
use App\View\View;

final class Router
{
  private $homeController;
  private $requests;
  private $view;
  public function __construct()
  {
    $this->homeController = new HomeController();
    $this->requests = new Requests();
  }
  /**
   * Récupérer la route et orienter vers le bon controller
   */
  public function run(): void
  {
    $this->homeController->home();
  }
}
