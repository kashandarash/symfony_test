<?php

namespace App\Form;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Translation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType {

  private array $locales = ['en', 'uk'];

  public function __construct(private EntityManagerInterface $entityManager) {}

  //  public function buildForm(FormBuilderInterface $builder, array $options): void {
  //    $builder
  //      ->add('title')
  //      ->add('description');
  //  }

  public function buildForm(FormBuilderInterface $builder, array $options): void {
    // Originals.
//    $builder
//      ->add('title')
//      ->add('description');

    // Add a form field for each locale's title and content
    foreach ($this->locales as $locale) {
      $builder->add('title_' . $locale, TextType::class, [
        'label' => 'Title (' . strtoupper($locale) . ')',
        'mapped' => FALSE,
        'required' => TRUE,
      ]);
      $builder->add('description_' . $locale, TextareaType::class, [
        'label' => 'Description (' . strtoupper($locale) . ')',
        'mapped' => FALSE,
        'required' => TRUE,
      ]);
    }

    // Add event listener to populate fields for existing entity
    $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
      $post = $event->getData();
      $form = $event->getForm();

      if (!$post || null === $post->getId()) {
        return;
      }

      $translationRepo = $this->entityManager->getRepository(Translation::class);
      $translations = $translationRepo->findTranslations($post);

      foreach ($this->locales as $locale) {
        if (isset($translations[$locale]['title'])) {
          $form->get('title_' . $locale)->setData($translations[$locale]['title']);
        }
        if (isset($translations[$locale]['description'])) {
          $form->get('description_' . $locale)->setData($translations[$locale]['description']);
        }
      }
    });

    // Add event listener to save translations on form submission
    $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
      $post = $event->getData();
      $form = $event->getForm();
      foreach ($this->locales as $locale) {
        $translationRepo = $this->entityManager->getRepository(Translation::class);
        $translationRepo->translate($post, 'title', $locale, $form->get('title_' . $locale)->getData());
        $translationRepo->translate($post, 'description', $locale, $form->get('description_' . $locale)->getData());
        $this->entityManager->flush();
      }
    });
  }

  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      'data_class' => Post::class,
    ]);
  }

}
