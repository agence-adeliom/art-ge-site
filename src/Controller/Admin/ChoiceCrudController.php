<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Choice;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ChoiceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Choice::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('Choix')
            ->setEntityLabelInPlural('Choix')
            ->showEntityActionsInlined()
            ->setPaginatorPageSize(200)
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);
        $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        $actions->remove(Crud::PAGE_INDEX, Action::DELETE);
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
        $actions->setPermission(Action::DELETE, 'ROLE_SUPER_ADMIN');
        $actions->setPermission(Action::EDIT, 'ROLE_SUPER_ADMIN');

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id');
        yield TextField::new('libelle');
        yield AssociationField::new('question');
    }
}
