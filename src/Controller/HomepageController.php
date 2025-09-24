<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class HomepageController extends AbstractController {

//  /**
//   * This route handles the root URL "/" and redirects to the
//   * localized homepage, using the default locale 'en'.
//   */
//  #[Route('/', name: 'root_redirect')]
//  public function rootRedirect(): Response {
//    // Redirect to the main homepage route with the default locale.
//    return $this->redirectToRoute('homepage', ['_locale' => 'en']);
//  }


  #[Route('/{_locale}/', name: 'homepage', requirements: ['_locale' => 'en|uk'])]
  public function index(TranslatorInterface $translator, Request $request, EntityManagerInterface $entityManager): Response {
    $posts = $entityManager->getRepository(Post::class)->findAll();

    return $this->render('page/homepage.html.twig', [
      'title' => $translator->trans('homepage_title', [], 'pages'),
      'posts' => $posts,
    ]);
  }

}
