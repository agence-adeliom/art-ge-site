<?php

namespace App\Controller\Admin;

use App\Entity\Repondant;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
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
        $actions->setPermission(Action::DELETE, 'ROLE_SUPER_ADMIN');
        $actions->setPermission(Action::EDIT, 'ROLE_SUPER_ADMIN');
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id');
        yield TextField::new('fullname', 'Nom')->onlyOnIndex();
        yield TextField::new('email');
        yield TextField::new('firstname')->hideOnIndex();
        yield TextField::new('lastname')->hideOnIndex();
        yield TextField::new('address')->hideOnIndex();
        yield TextField::new('zip')->hideOnIndex();
        yield TextField::new('city')->hideOnIndex();
        yield TextField::new('department', 'Département');
        yield TextField::new('country', 'Pays')->hideOnIndex();
        yield TextField::new('phone', 'Téléphone')->hideOnIndex();
        yield TextField::new('company', 'Entreprise')->hideOnIndex();
        yield TextField::new('typologie');
        yield BooleanField::new('restauration')->renderAsSwitch(false);
        yield BooleanField::new('greenSpace', 'Espace vert')->renderAsSwitch(false);
    }
}
