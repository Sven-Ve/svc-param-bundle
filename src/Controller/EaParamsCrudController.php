<?php

namespace Svc\ParamBundle\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Svc\ParamBundle\Entity\Params;

class EaParamsCrudController extends AbstractCrudController
{
  public static function getEntityFqcn(): string
  {
    return Params::class;
  }

  public function __construct(private bool $enableDeleteParam)
  {
  }

  public function configureCrud(Crud $crud): Crud
  {
    return parent::configureCrud($crud)
      ->setPageTitle(Crud::PAGE_INDEX, 'Parameter')
      ->showEntityActionsInlined()
//      ->setHelp(Crud::PAGE_INDEX, 'show results of the logging table in a raw format')
      ->setDefaultSort([
        'name' => 'asc',
      ]);
  }

  public function configureFields(string $pageName): iterable
  {
    yield IdField::new('id')
      ->onlyOnIndex();
    yield TextField::new('name')
      ->setDisabled();
    yield TextField::new('value')
      ->setTemplatePath('@SvcParam/ea/fields/ea-param-value-field.html.twig');
    yield ChoiceField::new('paramType')
      ->setChoices(Params::getTypesForChoices())
      ->setDisabled();
    yield TextField::new('comment');
  }

  public function configureActions(Actions $actions): Actions
  {
    $myActions = parent::configureActions($actions)
      ->disable(Action::NEW)
      ->disable(Action::DETAIL);

    if (!$this->enableDeleteParam) {
      $myActions->disable(Action::DELETE);
    }

    return $myActions;
  }

//  public function configureAssets(Assets $assets): Assets
//  {
//    return parent::configureAssets($assets)
//      ->addHtmlContentToBody("addHtmlContentToBody");
//  }
}
