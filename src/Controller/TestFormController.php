<?php

namespace App\Controller;

use App\ValueObject\TestForm;
use App\Form\TestFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class TestFormController extends AbstractController {

  #[Route('/test-form/{step}', name: 'test_form', requirements: ['step' => '\d+'], defaults: ['step' => 1])]
  public function index(int $step, Request $request, ValidatorInterface $validator): Response {

    if ($step !== 1 && $step !== 2) {
      return $this->redirectToRoute('test_form');
    }

    $session = $request->getSession();
    $sessionKey = 'test_form_data';

    // Get DTO from session or create a new one for the first step
    $data = $session->get($sessionKey, new TestForm());

    // Pass the current step to the form options
    $form = $this->createForm(TestFormType::class, $data, [
      'step' => $step,
    ]);

    $form->handleRequest($request);


    if ($form->isSubmitted() && $form->isValid()) {

      // Now we can do something with the data.
      // The data is from value object.
      // If we are here, constraints from form are pass.

      // Save the current state of our data into the session
      $session->set($sessionKey, $data);

      if ($step === 1) {
        // If step 1 is valid, redirect to step 2
        return $this->redirectToRoute('test_form', ['step' => 2]);
      }
      else {
        // Clean up the session so the next visit starts fresh
        $session->remove($sessionKey);

        // Check if prev step was filled.
        $violations = $validator->validate($data, null, ['step' . ($step - 1)]);
        if (count($violations) > 0) {
          // Clean up the session so the next visit starts fresh
          $this->addFlash('warning', 'Please complete the previous step first.');
          return $this->redirectToRoute('test_form', ['step' => $step - 1]);
        }

        // The data object now has all the data.
        $message_template = 'The form is submitted successfully. Data: "%s", "%s", "%s", "%s".';

        // Use sprintf() to insert the variables into the template
        $success = sprintf(
          $message_template,
          $data->field_one ?? '-',
          $data->field_two ?? '-',
          $data->email ?? '-',
          $data->message ?? '-',
        );

        // For demonstration, we'll just add a success message.
        $this->addFlash('success', $success);

        // Redirect back.
        return $this->redirectToRoute('test_form');
      }

    }

    if ($form->isSubmitted() && !$form->isValid()) {
      $this->addFlash('danger', 'Check form errors.');
    }

    return $this->render('page/test-form.html.twig', [
      'form' => $form->createView(),
      'step' => $step,
    ]);
  }

}
