<?php

namespace App\Form;

use App\ValueObject\TestForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

class TestFormType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    if ($options['step'] === 1) {
      $builder
        ->add('field_one', TextType::class, [
          'label' => 'Field one',
          'required' => TRUE,
          'label_html' => TRUE,
          'row_attr' => [
            'class' => 'col-sm-6',
          ],
          'label_attr' => [
            'class' => 'form-label',
          ],
          'attr' => [
            'class' => 'form-control',
          ],
        ])
        ->add('field_two', TextType::class, [
          'label' => 'Field two <span class="text-muted">(Optional)</span>',
          'label_html' => TRUE,
          'required' => FALSE,
          'row_attr' => [
            'class' => 'col-sm-6',
          ],
          'label_attr' => [
            'class' => 'form-label',
          ],
          'attr' => [
            'class' => 'form-control',
          ],
        ])
        ->add('email', EmailType::class, [
          'label' => 'Email  <span class="text-muted">(Optional)</span>',
          'label_html' => TRUE,
          'required' => FALSE,
          'row_attr' => [
            'class' => 'col-sm-12',
          ],
          'label_attr' => [
            'class' => 'form-label',
          ],
          'attr' => [
            'class' => 'form-control',
          ],
        ]);
    }

    if ($options['step'] === 2) {
      $builder
        ->add('message', TextareaType::class, [
          'required' => TRUE,
          'help' => 'Field is validated to have ? symbol',
          'help_attr' => [
            'class' => 'form-text text-muted',
          ],
          'row_attr' => [
            'class' => 'col-sm-12',
          ],
          'label_attr' => [
            'class' => 'form-label',
          ],
          'attr' => [
            'class' => 'form-control',
          ],
        ]);
    }

  }

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      'data_class' => TestForm::class,
      'step' => 1,
      // Make the validation groups dynamic based on the 'step' option
      'validation_groups' => function (Options $options) {
        return ['Default', 'step' . $options['step']];
      },
    ]);
    // Ensure the 'step' option is an integer
    $resolver->setAllowedTypes('step', 'int');
  }

}
