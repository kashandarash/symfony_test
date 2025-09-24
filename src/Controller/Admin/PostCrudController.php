<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\FormBuilderInterface;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

public function configureFilters(Filters $filters): Filters {
  return $filters
    ->add('title')
    ->add('description');
}

  public function configureFields(string $pageName): iterable
  {
    if (Crud::PAGE_INDEX === $pageName || Crud::PAGE_DETAIL === $pageName) {
      // For the Index and Detail views, display the current locale's content
      yield TextField::new('title');
      yield TextareaField::new('description');
    } else {
      yield TextField::new('translations')
        ->setLabel('Translations')
        ->setFormType(PostType::class)
        ->setFormTypeOptions([
          'mapped' => false,
          'data' => $this->getContext()->getEntity()->getInstance(),
        ]);
    }
  }
}
