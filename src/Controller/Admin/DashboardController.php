<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\PlanFeature;
use App\Entity\Post;
use App\Entity\PricingPlan;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController {

  public function __construct(private EntityManagerInterface $entityManager) {}

  public function index(): Response {

    return $this->render('admin/dashboard.html.twig', [
      'pricing_plans' => $this->entityManager->getRepository(PricingPlan::class)->count(),
      'posts' =>  $this->entityManager->getRepository(Post::class)->count(),
      'articles' =>  $this->entityManager->getRepository(Article::class)->count(),
    ]);
  }

  public function configureDashboard(): Dashboard {
    return Dashboard::new()
      ->setTitle('Dashboard');
  }

  public function configureMenuItems(): iterable {
    yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
    //yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    yield MenuItem::linkToCrud('Pricing Plans', 'fas fa-list', PricingPlan::class);
    yield MenuItem::linkToCrud('Plan Features', 'fas fa-list', PlanFeature::class);
    yield MenuItem::linkToCrud('Posts', 'fas fa-list', Post::class);
    yield MenuItem::linkToCrud('Articles', 'fas fa-list', Article::class);
  }

}
