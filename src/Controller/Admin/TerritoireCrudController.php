<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Territoire;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class TerritoireCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly RouterInterface $router,
    ) {}

    public static function getEntityFqcn(): string
    {
        return Territoire::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        // easy.page.admin.crud.label.landingPage.new
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, 'Territoires')
            ->setPageTitle(Crud::PAGE_DETAIL, 'Territoire')
            ->setPageTitle(Crud::PAGE_EDIT, 'Territoire')
            ->showEntityActionsInlined()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);
        $actions->add(Crud::PAGE_INDEX, Action::new('territoire.view', 'Dashboard', 'fas fa-eye')->linkToCrudAction('view'));
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
        $actions->remove(Crud::PAGE_INDEX, Action::DETAIL);

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('uuid', 'Identifiant')->hideOnForm();
        yield TextField::new('name');
        yield TextField::new('slug');
        yield BooleanField::new('useSlug', Crud::PAGE_INDEX === $pageName ? 'Utiliser le slug ?' : 'Utiliser le slug comme identifiant de l\'URL du dashboard ?')->renderAsSwitch(Crud::PAGE_EDIT === $pageName);
        yield TextField::new('code');
        yield CollectionField::new('zips', 'Codes postaux');
        yield ImageField::new('postalCodesFile', 'Fichier CSV des codes postaux de la station')
            ->hideOnIndex()
            ->setHelp('Il faut que la première colonne du fichier soit une liste de code postaux / numéros de département. <br /> La première ligne n\'est pas prise en compte car c\'est l\'entête de la colonne')
            ->setUploadDir('var/upload/files')
            ->setFormTypeOption('mapped', false)
            ->setUploadedFileNamePattern('[year]/[month]/[day]/[slug]-[contenthash].[extension]')
        ;
    }

    public function view(AdminContext $context): Response
    {
        /** @var Territoire|null $object */
        $object = $context->getEntity()->getInstance();

        if (!$object) {
            throw new NotFoundHttpException('Unable to find the reponse');
        }

        $identifier = $object->isUseSlug() ? $object->getSlug() : $object->getUuid();

        $url = $this->router->generate('app_territoire_single', ['identifier' => $identifier], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->redirect($url);
    }
}
