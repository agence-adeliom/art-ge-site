<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Thematique;
use App\Form\Admin\ThematiqueLinkAdminType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ThematiqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Thematique::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('Thématique')
            ->setEntityLabelInPlural('Thématiques')
            ->showEntityActionsInlined()
            ->setPaginatorPageSize(100)
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);
        $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        $actions->remove(Crud::PAGE_INDEX, Action::DELETE);
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
        $actions->setPermission(Action::DELETE, 'ROLE_SUPER_ADMIN');

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield CollectionField::new('links', 'Liens')
            ->setEntryType(ThematiqueLinkAdminType::class)
        ;
    }
}
