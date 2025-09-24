<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ArticleCrudController extends AbstractCrudController {

  public function __construct(private EntityManagerInterface $em) {}

  public static function getEntityFqcn(): string {
    return Article::class;
  }

  public function configureFilters(Filters $filters): Filters {
    return $filters
      ->add('published')
      ->add(ChoiceFilter::new('locale')->setChoices([
        'English' => 'en',
        'Українська' => 'uk',
      ]));
  }

  public function configureActions(Actions $actions): Actions {
    $cloneAction = Action::new('clone', 'Clone')->linkToCrudAction('cloneArticle');
    $resetSort = Action::new('resetAll', 'Reset All')->linkToUrl($this->generateUrl('admin_article_index'))->createAsGlobalAction();

    return $actions
      // This correctly adds the 'clone' action to each row's dropdown menu.
      ->add(Crud::PAGE_INDEX, $cloneAction)
      // This adds the global action to the top of the index page.
      ->add(Crud::PAGE_INDEX, $resetSort);
  }

  public function configureFields(string $pageName): iterable {
    if (Crud::PAGE_INDEX === $pageName || Crud::PAGE_DETAIL === $pageName) {
      // For the Index and Detail views, display the current locale's content
      yield IdField::new('id');
    }
    yield TextField::new('title');
    yield TextEditorField::new('content');
    yield ChoiceField::new('locale')
      ->setChoices([
        'English' => 'en',
        'Українська' => 'uk',
      ]);
    yield BooleanField::new('published');
  }


  public function cloneArticle(AdminContext $context): Response {
    /** @var Article $articleToClone */
    $entity_id = $context->getRequest()->query->get('entityId');
    $entity = $this->em->getRepository(Article::class)->find($entity_id);
    if (!$entity) {
      $this->addFlash('warning', 'Entity was no cloned.');
      return $this->redirect($this->generateUrl('admin_article_index'));
    }

    // Use PHP's native clone keyword
    $cloned_article = clone $entity;

    // Persist the new, cloned entity
    $this->em->persist($cloned_article);
    $this->em->flush();

    $this->addFlash('success', 'Product has been cloned successfully! 🐑');

    // Redirect to the 'edit' page of the newly created clone
    return $this->redirect($this->generateUrl('admin_article_edit', ['entityId' => $cloned_article->getId()]));
  }

}
