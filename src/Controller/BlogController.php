<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class BlogController extends AbstractController {

  /**
   * Define the number of articles to display per page.
   */
  private const ARTICLES_PER_PAGE = 3;

  #[Route('/{_locale}/blog', name: 'blog', requirements: ['_locale' => 'en|uk'])]
  public function index(EntityManagerInterface $entityManager, Request $request): Response {
    $current_page = $request->query->getInt('page', 1);
    $search = substr(trim($request->query->get('search')), 0, 50);
    $order = $request->query->get('order', 'DESC');

    $params = ['locale' => $request->getLocale(), 'published' => TRUE];

    $articles_total = $entityManager->getRepository(Article::class)->countArticles($params, $search);

    // Calculate the total number of pages needed.
    $max_pages = ceil($articles_total / self::ARTICLES_PER_PAGE);

    // If user type not existing page, redirect to first page.
    if ($current_page > $max_pages) {
      return $this->redirectToRoute('blog');
    }

    $offset = ($current_page - 1) * self::ARTICLES_PER_PAGE;
    $articles = $entityManager->getRepository(Article::class)
      ->findArticles($params, $order, $search, self::ARTICLES_PER_PAGE, $offset);

    return $this->render('page/blog.html.twig', [
      'articles' => $articles,
      'max_pages' => $max_pages,
      'current_page' => $current_page,
      'search' => $search,
      'order' => $order,
    ]);
  }

}
