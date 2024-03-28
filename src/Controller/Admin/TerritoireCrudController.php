<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Territoire;
use App\Enum\TerritoireAreaEnum;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class TerritoireCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly RouterInterface $router,
        private readonly AdminContextProvider $adminContextProvider,
    ) {
    }

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
        /** @var Territoire | null $instance */
        $instance = $this->adminContextProvider->getContext()->getEntity()->getInstance();
        yield FormField::addTab('Informations');
        yield TextField::new('uuid', 'Identifiant')->hideOnForm();
        yield TextField::new('name', 'Nom');
        yield TextField::new('slug', 'Identifiant unique (slug)');
        if ($instance instanceof Territoire && $instance->getArea() !== TerritoireAreaEnum::REGION && $instance->getArea() !== TerritoireAreaEnum::DEPARTEMENT) {
            yield ChoiceField::new('area', 'Type de territoire')->setChoices([
                'Office de tourisme' => TerritoireAreaEnum::OT,
                'Territoire sur-mesure' => TerritoireAreaEnum::TOURISME,
            ]);
        }
        yield BooleanField::new('useSlug', Crud::PAGE_INDEX === $pageName ? 'Utiliser le slug ?' : 'Utiliser le slug dans l\'URL du dashboard ?')->renderAsSwitch(Crud::PAGE_EDIT === $pageName);

        yield FormField::addTab('Autorisations');
        yield BooleanField::new('isPublic', 'Public')->renderAsSwitch(Crud::PAGE_EDIT === $pageName)->setHelp('Si public est coché, la page n\'est pas protégée par un mot de passe');
        yield TextField::new('code', Crud::PAGE_INDEX === $pageName ? 'Code' : 'Code d\'accès au dashboard')->setHelp('Si public est décoché, c\'est ce code qu\'il faut rentrer pour accéder à la page');

        yield FormField::addTab('Codes postaux');
        yield AssociationField::new('cities', Crud::PAGE_INDEX === $pageName ? 'Codes Insée' : 'Édition manuelle des codes Insée');
        /** @var string $uploadDirectory */
        $uploadDirectory = $this->parameterBag->get('upload_directory');
        yield ImageField::new('inseeCodesFile', 'Ajout en groupe des codes Insée via un fichier CSV')
            ->hideOnIndex()
            ->setHelp('Il faut que la première colonne du fichier soit une liste de code Insée / numéros de département. <br /> La première ligne n\'est pas prise en compte car c\'est l\'entête de la colonne<br /><a download="insee_territoire_modele.csv" href="data:text/csv;charset=utf-8,insee%0A08001%0A67500%0Aetc...">Télécharger le modèle CSV <i class="fa fa-download"></i></a>')
            ->setUploadDir($uploadDirectory)
            ->setFormTypeOption('mapped', false)
            ->setUploadedFileNamePattern('[year]/[month]/[day]/[slug]-[contenthash].[extension]')
        ;

        yield FormField::addTab('Relation');
        if ($instance instanceof Territoire && $instance->getArea() !== TerritoireAreaEnum::REGION && $instance->getArea() !== TerritoireAreaEnum::DEPARTEMENT) {
            yield AssociationField::new('parents', 'Territoires parents')->hideOnIndex();
        }
        yield AssociationField::new('linkedTerritoires', 'Quels territoires peuvent accéder à ce territoire ?')
            ->hideOnIndex()
            ->setHelp('Cette information est seulement utilisée pour afficher ou non le territoire sur les dashboard des territoires sélectionnés.')
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
