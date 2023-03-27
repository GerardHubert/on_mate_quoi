<?php

declare(strict_types=1);

namespace App\Controller;

use App\View\View;
use App\Model\Manager\GenreManager;

class HomeController
{
  private string $key;
  private $view;
  private $genreManager;
  public function __construct(GenreManager $genreManager)
  {
    $this->view = new View();
    $this->key = $_ENV["API_KEY"];
    $this->genreManager = $genreManager;
  }

  public function home(): void
  {
    // on requête pour avoir la liste des genres
    // on propose au client de cocher les genres qui l'intéresse
    // on renvoie une liste de fils qui correspondent à ces genres
    $queryToGetGenres = "https://api.themoviedb.org/3/genre/movie/list?api_key=" . $this->key . "&language=fr-FR";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $queryToGetGenres);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $results = curl_exec($curl);
    if (!empty(curl_error($curl))) {
      var_dump(curl_error($curl));
      die;
    }
    curl_close($curl);
    $array = json_decode($results, true);
    $genres = $array['genres'];

    //on enregistre les genres dans la base de donneés
    $this->genreManager->saveGenre($genres);

    $this->view->render('home.html.twig', [
      // "genres" => $array['genres']
    ]);
  }
}
