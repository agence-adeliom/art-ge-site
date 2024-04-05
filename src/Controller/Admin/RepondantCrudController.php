<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Repondant;
use App\Form\Admin\ReponseAdminType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RepondantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Repondant::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        // easy.page.admin.crud.label.landingPage.new
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, 'Répondants')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Répondant')
            ->setPageTitle(Crud::PAGE_EDIT, 'Répondant')
            ->showEntityActionsInlined()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);
        $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        if (Crud::PAGE_EDIT !== $pageName) {
            yield IdField::new('id');
            yield TextField::new('fullname', 'Prénom Nom')->onlyOnIndex();
            yield TextField::new('email');
            yield TextField::new('firstname', 'Prénom')->hideOnIndex();
            yield TextField::new('lastname', 'Nom de famille')->hideOnIndex();
            yield TextField::new('address', 'Adresse')->hideOnIndex();
            yield TextField::new('zip', 'Code postal')->hideOnIndex();
            yield TextField::new('insee', 'Code INSEE')->hideOnIndex();
            yield TextField::new('city', 'Ville')->hideOnIndex();
            yield TextField::new('department', 'Département');
            yield TextField::new('country', 'Pays')->hideOnIndex();
            yield TextField::new('phone', 'Téléphone')->hideOnIndex();
            yield TextField::new('company', 'Entreprise');
            yield TextField::new('typologie');
            yield BooleanField::new('restauration')->renderAsSwitch(false);
            yield BooleanField::new('greenSpace', 'Espace vert')->renderAsSwitch(false);
            yield CollectionField::new('reponses')
                ->setEntryType(ReponseAdminType::class)
                ->setTemplatePath('admin/crud/reponse_admin.html.twig')
                ->hideOnIndex()
            ;
        } else {
            yield TextField::new('email');
            yield TextField::new('firstname', 'Prénom');
            yield TextField::new('lastname', 'Nom');
            yield TextField::new('address', 'Adresse');
            yield TextField::new('zip', 'Code postal');
            yield TextField::new('insee', 'Code INSEE');
            yield TextField::new('city', 'Ville');
            yield AssociationField::new('department', 'Département');
            yield TextField::new('country', 'Pays');
            yield TextField::new('phone', 'Téléphone');
            yield TextField::new('company', 'Entreprise');
            yield AssociationField::new('typologie');
        }
    }

    public function configureFilters(Filters $filters): Filters
    {
        $filters
            ->add('city')
            ->add('department')
        ;

        return $filters;
    }
}
