<?php

declare(strict_types=1);

namespace App\Services;

use App\View\View;
use App\Services\Database;
use App\Services\Http\Session;
use App\Services\Http\Requests;
use App\Controller\FilmController;
use App\Controller\HomeController;
use App\Controller\SearchController;
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
  private $session;
  private $filmController;
  private $searchController;

  public function __construct()
  {
    $this->database = new Database();
    $this->session = new Session();
    $this->genreRepository = new GenreRepository($this->database);
    $this->genreManager = new GenreManager($this->genreRepository);
    $this->view = new View();
    $this->homeController = new HomeController($this->view, $this->genreManager, $this->session);
    $this->filmController = new FilmController($this->view, $this->session);
    $this->searchController = new SearchController($this->view);
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
      case 'select-genre':
        $this->homeController->selectGenre($this->requests);
        break;
      case 'details':
        $this->filmController->showOne($route);
        break;
      case 'query':
        $this->searchController->search($this->requests);
        break;
      case 'best-rated':
        $this->filmController->showBestRated($this->genreManager);
        break;
      case 'most-popular':
        $this->filmController->showMostPopular();
        break;
      default:
        $this->homeController->home($this->genreManager);
        break;
    }
  }
}
