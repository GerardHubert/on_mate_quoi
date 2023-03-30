<?php

declare(strict_types=1);

namespace App\Controller;

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
  public function showOne(array $route)
  {
    // on récupère l'id du film délectionné
    $id = $route['id'];
    var_dump($id);

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

    var_dump($details);


    return $this->view->render('film_details.html.twig', []);
  }
}
