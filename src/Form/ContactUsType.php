<?php

namespace App\Form;

use App\ValueObject\ContactUsFrom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactUsType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $attributes = [
     'required' => TRUE,
      'row_attr' => [
        'class' => 'mb-3',
      ],
      'label_attr' => [
        'class' => 'col-form-label',
      ],
      'attr' => [
        'class' => 'form-control',
      ],
      'constraints' => [
        new NotBlank(),
      ]
    ];
    $builder
      ->add('name', TextType::class, $attributes)
      ->add('email', EmailType::class, $attributes)
      ->add('message', TextareaType::class, $attributes)
//      ->add('submit', SubmitType::class, [
//        'attr' => [
//          'class' => 'btn btn-primary ms-auto',
//        ],
//        'row_attr' => [
//          'class' => 'd-flex',
//        ],
//        'label' => 'Submit',
//      ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      'data_class' => ContactUsFrom::class,
    ]);
  }

}
