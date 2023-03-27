<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Genre;
use App\Services\Database;
use \PDO;

class GenreRepository
{
  public function __construct(private Database $database)
  {
  }

  public function saveGenre(Genre $genre): bool
  {
    $name = $genre->getGenreName();
    $apiId = $genre->getGenreApiId();

    $query = $this->database->prepare(
      "INSERT INTO genres (name, api_id) VALUES (:name, :apiId)"
    );

    $query->bindParam('name', $name);
    $query->bindParam('apiId', $apiId);

    return $query->execute();
  }
}
