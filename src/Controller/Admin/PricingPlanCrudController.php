<?php

namespace App\Controller\Admin;

use App\Entity\PricingPlan;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PricingPlanCrudController extends AbstractCrudController {

  public static function getEntityFqcn(): string {
    return PricingPlan::class;
  }


  public function configureFields(string $pageName): iterable {
    return [
      TextField::new('name'),
      IntegerField::new('price'),
      AssociationField::new('features')->setCrudController(PlanFeatureCrudController::class)->onlyOnForms(),
    ];
  }

}
