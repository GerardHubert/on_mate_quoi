<?php

declare(strict_types=1);

namespace App\Controller;

use App\View\View;
use App\Model\Manager\GenreManager;
use App\Services\Http\Requests;
use App\Services\Http\Session;
use DateInterval;
use DateTime;
use DateTimeInterface;

class HomeController
{
  private string $key;
  private $view;
  private $genreManager;
  private $session;
  public function __construct(View $view, GenreManager $genreManager, Session $session)
  {
    $this->view = $view;
    $this->key = $_ENV["API_KEY"];
    $this->genreManager = $genreManager;
    $this->session = $session;
  }

  public function home(): void
  {
    // créer une période sur laquelle récupérer les films sortis
    $today = new DateTime('now');
    $interval = new DateTime('now');
    $interval->sub(new DateInterval('P15D'));
    $intervalFormated = $interval->format('Y-m-d');
    $todayFormated = $today->format("Y-m-d");

    // récupérer les films du moment (sortis il y a moins de x semaines) pour affichage en page accueil
    $url = "https://api.themoviedb.org/3/discover/movie?api_key=" . $this->key . "&language=fr-FR&sort_by=popularity.desc&include_adult=false&include_video=true&page=1&primary_release_date.gte=" . $intervalFormated . "&release_date.lte=" . $todayFormated;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($curl);

    if (!empty(curl_error($curl))) {
      $this->session->add('curl-error', curl_error($curl));
      $this->view->render('home.html.twig', []);
      return;
    }

    curl_close($curl);
    $results = json_decode($data, true);

    // on récupère les genres enregistrés en base de données
    $genres = $this->genreManager->getGenres();

    // on rend le tout disponible dans le rendu pour laisser le choix au client
    $this->view->render('home.html.twig', [
      "genres" => $genres,
      "actualFilms" => $results['results']
    ]);
  }

  public function selectGenre(Requests $request): void
  {
    // du genre séléctionné, on en fait un objet
    $data = $request->post()['genres'];
    $genre = $this->genreManager->getOneGenre((int) $data);

    // faire une requête pour récupérer des films du genre demandé
    $url = "https://api.themoviedb.org/3/discover/movie?api_key=" . $this->key . "&language=fr-FR&with_genres=" . $data . "&sort_by=popularity.desc&include_adult=false&page=1";
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($curl);

    // gérer l'erreur si le requête renvoie n'aaboutit as et retourne false
    if (!empty(curl_error($curl))) {
      $error = curl_error($curl);
      $this->session->add("curl-error", $error);
      $this->view->render('home.html.twig', []);
      return;
    };

    curl_close($curl);
    $results = json_decode($data, true);

    $this->view->render("genre_results.html.twig", [
      'films' => $results['results'],
      'genre' => $genre
    ]);
  }
}
