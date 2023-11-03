<?php

namespace App\Controller\Admin;

use App\Entity\Reponse;
use App\Form\Admin\ScoreAdminType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReponseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reponse::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        // easy.page.admin.crud.label.landingPage.new
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, 'Réponses')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Réponse')
            ->setPageTitle(Crud::PAGE_EDIT, 'Réponse')
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
        yield AssociationField::new('repondant');
        yield TextField::new('uuid');
        yield DateField::new('createdAt', 'Commencé le');
        yield DateField::new('submittedAt', 'Envoyé le');
        yield NumberField::new('points');
        yield NumberField::new('total');
        yield BooleanField::new('completed')->renderAsSwitch(false);
        yield CollectionField::new('scores')
            ->setEntryType(ScoreAdminType::class)
            ->setTemplatePath('admin/crud/score_admin.html.twig')
            ->hideOnIndex()
        ;
    }
}
