<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post implements Translatable {

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = NULL;

  #[Gedmo\Translatable]
  #[ORM\Column(length: 255)]
  private ?string $title = NULL;

  #[Gedmo\Translatable]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = NULL;

  #[Gedmo\Locale]
  private ?string $locale = NULL;

  public function getLocale(): ?string {
    return $this->locale;
  }

  public function setTranslatableLocale(?string $locale): void {
    $this->locale = $locale;
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getTitle(): ?string {
    return $this->title;
  }

  public function setTitle(string $title): static {
    $this->title = $title;

    return $this;
  }

  public function getDescription(): ?string {
    return $this->description;
  }

  public function setDescription(string $description): static {
    $this->description = $description;

    return $this;
  }

}
