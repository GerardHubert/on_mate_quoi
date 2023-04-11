<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Genre;
use App\Model\Manager\GenreManager;
use App\Services\Http\Session;
use App\View\View;

class FilmController
{
  private string $key;
  private View $view;
  private Session $session;

  public function __construct($view, $session)
  {
    $this->view = $view;
    $this->key = $_ENV['API_KEY'];
    $this->session = $session;
  }

  // méthode pour montrer les détails d'un film
  public function showOne(array $route): void
  {
    // on récupère l'id du film délectionné
    $id = $route['id'];

    // requête pur récupérer les détails
    $detailsUrl = "https://api.themoviedb.org/3/movie/" . $id . "?api_key=" . $this->key . "&language=fr-FR";
    $curl = curl_init($detailsUrl);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($curl);

    if (!empty(curl_error($curl))) {
      $this->session->add('curl_error', curl_error($curl));
      var_dump($this->session->getSession());
      return;
    }

    curl_close($curl);

    // requête pour récupérer les vidéos
    $videoUrl = "https://api.themoviedb.org/3/movie/" . $id . "/videos?api_key=" . $this->key .  "&language=fr-FR";
    $curl2 = curl_init($videoUrl);
    curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
    $data2 = curl_exec($curl2);

    if (!empty(curl_error($curl2))) {
      $this->session->add('curl_error', curl_error($curl2));
      var_dump($this->session->getSession());
      return;
    }

    curl_close($curl2);

    // requête pour récupérer les crédits
    $creditsUrl = "https://api.themoviedb.org/3/movie/" . $id . "/credits?api_key=" . $this->key . "&language=fr-FR";
    $curl3 = curl_init($creditsUrl);
    curl_setopt($curl3, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl3, CURLOPT_RETURNTRANSFER, true);
    $data3 = curl_exec($curl3);

    if (!empty(curl_error($curl3))) {
      $this->session->add('curl_error', curl_error($curl3));
      var_dump($this->session->getSession());
      return;
    }

    curl_close($curl3);

    $details = json_decode($data, true);
    $videos = json_decode($data2, true);
    $credits = json_decode($data3, true);

    if (!empty($videos['results'])) {
      $lastTrailer = array_splice($videos['results'], 0, 1);
    } else {
      $lastTrailer[0] = "";
    }

    $this->view->render('film_details.html.twig', [
      "details" => $details,
      "lastTrailer" => $lastTrailer[0],
      "videos" => $videos['results'],
      "cast" => $credits['cast'],
      "crew" => $credits['crew']
    ]);
  }

  // afficher les 7 films les mieux notés de chaque genre
  public function showBestRated(GenreManager $genreManager): void
  {
    //récupérer les genres
    $genres = $genreManager->getGenres();

    // récupérer les films les mieux notés pour chaque genre
    $bestOfEachGenre = [];

    foreach ($genres as $genre) {
      $curl = curl_init("https://api.themoviedb.org/3/discover/movie?api_key=" . $this->key . "&language=fr-FR&sort_by=vote_average.desc&include_adult=false&include_video=false&page=1&with_genres=" . $genre->getGenreApiId());
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

      $results = curl_exec($curl);
      if (curl_error($curl)) {
        var_dump(curl_error($curl));
        return;
      }

      curl_close($curl);

      $decoded = json_decode($results, true);
      $bestOfEachGenre[$genre->getGenreName()] = $decoded['results'];
    };

    $this->view->render('best_rated.html.twig', [
      "genres" => $genres,

      "top" => [
        "western" => $bestOfEachGenre['Western'],
        "guerre" => $bestOfEachGenre['Guerre'],
        "thriller" => $bestOfEachGenre['Thriller'],
        "telefilm" => $bestOfEachGenre['Téléfilm'],
        "sf" => $bestOfEachGenre['Science-Fiction'],
        "romance" => $bestOfEachGenre['Romance'],
        "mystere" => $bestOfEachGenre['Mystère'],
        "musique" => $bestOfEachGenre['Musique'],
        "horreur" => $bestOfEachGenre['Horreur'],
        "histoire" => $bestOfEachGenre['Histoire'],
        "fantastique" => $bestOfEachGenre['Fantastique'],
        "familial" => $bestOfEachGenre['Familial'],
        "drame" => $bestOfEachGenre['Drame'],
        "documentaire" => $bestOfEachGenre['Documentaire'],
        "crime" => $bestOfEachGenre['Crime'],
        "animation" => $bestOfEachGenre['Animation'],
        "aventure" => $bestOfEachGenre['Aventure'],
        "action" => $bestOfEachGenre['Action']
      ]
    ]);
  }

  public function showMostPopular(): void
  {
    $curl = curl_init("https://api.themoviedb.org/3/discover/movie?api_key=" . $this->key . "&language=fr-FR&sort_by=popularity.desc&include_adult=false&include_video=false&page=1");
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $results = curl_exec($curl);
    if (curl_error($curl)) {
      var_dump(curl_error($curl));
      return;
    }

    $decoded = json_decode($results, true);

    $this->view->render("most_popular.html.twig", [
      "films" => $decoded['results']
    ]);
  }
}
