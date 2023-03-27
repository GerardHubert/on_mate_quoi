<?php

declare(strict_types=1);

namespace App\Model\Entity;

final class Genre
{
  private int $genreApiId;
  private int $id;
  private string $genreName;

  public function getId(): int
  {
    return $this->id;
  }

  public function getGenreApiId(): int
  {
    return $this->genreApiId;
  }

  public function getGenreName(): string
  {
    return $this->genreName;
  }

  public function setGenreApiId($genreApiId): self
  {
    $this->genreApiId = $genreApiId;
    return $this;
  }

  public function setGenreName($genreName): self
  {
    $this->genreName = $genreName;
    return $this;
  }
}
