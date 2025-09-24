<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article {

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = NULL;

  #[ORM\Column(length: 255)]
  private ?string $title = NULL;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $content = NULL;

  #[ORM\Column(length: 5)]
  private string $locale = 'en';

  #[ORM\Column]
  private bool $published = FALSE;

  public function getId(): ?int {
    return $this->id;
  }

  public function getTitle(): ?string {
    return $this->title;
  }

  /**
   * Dynamic setter,
   *
   * @param string $field
   * @param string $value
   *
   * @return $this
   */
  public function set(string $field, string $value): static {
    $this->$field = $value;
    return $this;
  }

  public function setTitle(string $title): static {
    $this->title = $title;

    return $this;
  }

  public function getContent(): ?string {
    return $this->content;
  }

  public function setContent(string $content): static {
    $this->content = $content;

    return $this;
  }

  public function getLocale(): string {
    return $this->locale;
  }

  public function setLocale(string $locale): static {
    $this->locale = $locale;

    return $this;
  }

  public function isPublished(): bool {
    return $this->published;
  }

  public function setPublished(bool $published): static {
    $this->published = $published;

    return $this;
  }

}
