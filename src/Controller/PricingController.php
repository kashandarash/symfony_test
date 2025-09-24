<?php

namespace App\Controller;

use App\Entity\PricingPlan;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PricingController extends AbstractController
{
    #[Route('/{_locale}/pricing', name: 'pricing', requirements: ['_locale' => 'en|uk'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
       $pricing_plans = $entityManager->getRepository(PricingPlan::class)->findAll();

        return $this->render('page/pricing.html.twig', [
            'pricing_plans' => $pricing_plans,
        ]);
    }
}
