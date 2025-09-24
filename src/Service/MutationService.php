<?php

namespace App\Service;

use App\Entity\Article;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Error\Error;

class MutationService
{
  public function __construct(
    private EntityManagerInterface $manager
  ) {}

  public function createArticle(array $values): Article
  {
    $article = new Article();
    $article->setTitle($values['title']);
    $article->setContent($values['content']);
    $article->setLocale($values['locale']);
    $this->manager->persist($article);
    $this->manager->flush();

    return $article;
  }

  public function updateArticle(array $values): Article {

    $article = $this->manager->getRepository(Article::class)->find($values['id'] ?? null);

    if (is_null($article)) {
      throw new Error("Could not find Article for specified ID");
    }

    foreach ($values as $key => $value) {
      $article->set($key, $value);
    }
    $this->manager->persist($article);
    $this->manager->flush();

    return $article;


  }


}