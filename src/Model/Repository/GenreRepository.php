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
      "INSERT INTO genres (name, genreApiId) VALUES (:name, :apiId)"
    );

    $query->bindParam(':name', $name);
    $query->bindParam(':apiId', $apiId);

    return $query->execute();
  }

  public function findAll(): false|array // mode union-type
  {
    $request = $this->database->prepare("SELECT * FROM genres");
    $request->setFetchMode(PDO::FETCH_CLASS, Genre::class);
    $request->execute();

    return $request->fetchAll();
  }
  public function findOneByApiId(int $id): ?Genre
  {
    $request = $this->database->prepare("SELECT * FROM genres WHERE genreApiId = :apiId");
    $request->bindParam(':apiId', $id);
    $request->setFetchMode(PDO::FETCH_CLASS, Genre::class);
    $request->execute();

    return $request->fetch();
  }
}
