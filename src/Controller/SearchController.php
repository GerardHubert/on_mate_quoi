<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\Http\Requests;
use App\View\View;

class SearchController
{
  private $key;
  public function __construct(
    private View $view
  ) {
    $this->key = $_ENV['API_KEY'];
  }

  public function search(Requests $requests)
  {
    $query = $requests->post()['search'];
    $url = "https://api.themoviedb.org/3/search/multi?api_key=" . $this->key . "&language=fr-FR&query=" . $query . "&page=1&include_adult=false";

    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $data = curl_exec($curl);

    if (!empty(curl_error($curl))) {
      var_dump(curl_error($curl));
      return;
    }
    $results = json_decode($data, true);

    // on n'affiche que les resultats dont le "media_type" est "movie"
    $this->view->render("query_results.html.twig", [
      "results" => $results['results']
    ]);
  }
}
