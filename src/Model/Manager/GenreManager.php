<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\Genre;
use App\Model\Repository\GenreRepository;

class GenreManager
{
  public function __construct(private GenreRepository $genreRepository)
  {
  }

  public function saveGenre(array $genres)
  {
    foreach ($genres as $key => $value) {
      $genre = new Genre;
      $genre->setGenreApiId((int) $value['id']);
      $genre->setGenreName((string) $value['name']);
      $this->genreRepository->saveGenre($genre);
    }
  }

  public function getGenres(): bool|array
  {
    return $this->genreRepository->findAll();
  }

  public function getOneGenre(int $apiId): ?Genre
  {
    return $this->genreRepository->findOneByApiId($apiId);
  }
}
