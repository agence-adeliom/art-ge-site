<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Epci;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EpciCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Epci::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('EPCI')
            ->setEntityLabelInPlural('EPCIs')
            ->showEntityActionsInlined()
        ;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return parent::configureAssets($assets)->addHtmlContentToHead(
            '<style>form[name="Epci"] .ts-control { display: block; } form[name="Epci"] .ts-wrapper.multi .ts-control > div.item { display: block; width: fit-content; }</style>'
        );
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name');
        yield TextField::new('slug');
        yield TextField::new('siren');
        yield AssociationField::new('cities', 'Villes');
        yield AssociationField::new('territoires');
    }
}
