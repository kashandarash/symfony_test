<?php

namespace App\Controller;

use App\Entity\PricingPlan;
use App\Form\ContactUsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactUsController extends AbstractController {

  #[Route('/contact-us', name: 'contact_us', methods: ['POST'])]
  public function index(Request $request): Response {

    $form = $this->createForm(ContactUsType::class);


    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $data = $form->getData();
      // Now we can do something with the data.

      $this->addFlash('success', 'Thank you! We will contact you soon.');

      // Redirect to home page.
      return $this->redirectToRoute('homepage');
    }


    return $this->render('widget/contact_us.html.twig', ['form' => $form->createView()]);
  }

}
