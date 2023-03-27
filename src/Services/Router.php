<?php

declare(strict_types=1);

namespace App\Services;

use App\View\View;
use App\Services\Database;
use App\Services\Http\Requests;
use App\Controller\HomeController;
use App\Model\Manager\GenreManager;
use App\Model\Repository\GenreRepository;

final class Router
{
  private $database;
  private $genreManager;
  private $homeController;
  private $requests;
  private $view;
  private $genreRepository;
  public function __construct()
  {
    $this->database = new Database();
    $this->genreRepository = new GenreRepository($this->database);
    $this->genreManager = new GenreManager($this->genreRepository);
    $this->homeController = new HomeController($this->genreManager);
    $this->requests = new Requests();
  }
  /**
   * Récupérer la route et orienter vers le bon controller
   */
  public function run(): void
  {
    $route = $this->requests->get();
    empty($route) ? $action = "home" : $action = $route["action"];

    switch ($action) {
      case 'home':
        $this->homeController->home($this->genreManager);
        break;
      default:
        $this->homeController->home($this->genreManager);
        break;
    }
  }
}
