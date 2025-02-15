<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Choice;
use App\Entity\Reponse;
use App\Form\Admin\ScoreAdminType;
use App\Repository\ChoiceRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ReponseCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly ChoiceRepository $choiceRepository,
        private readonly AdminContextProvider $adminContextProvider,
    ) {
    }

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
        $actions->add(Crud::PAGE_INDEX, Action::new('reponse.view', 'Dashboard', 'fas fa-eye')->linkToCrudAction('view')->setHtmlAttributes(['target' => '_blank']));
        $actions->add(Crud::PAGE_DETAIL, Action::new('reponse.view', 'Dashboard', 'fas fa-eye')->linkToCrudAction('view')->setHtmlAttributes(['target' => '_blank']));
        $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        $actions->remove(Crud::PAGE_INDEX, Action::EDIT);
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
        $actions->remove(Crud::PAGE_DETAIL, Action::EDIT);
        $actions->setPermission(Action::EDIT, 'ROLE_SUPER_ADMIN');

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id');
        yield AssociationField::new('repondant');
        yield TextField::new('uuid', 'Identifiant');
        yield DateField::new('createdAt', 'Commencé le');
        yield DateField::new('submittedAt', 'Envoyé le');
        yield NumberField::new('points');
        yield NumberField::new('total');
        yield TextField::new('percentage', 'Note')->hideOnForm();
        yield BooleanField::new('completed')->renderAsSwitch(false);
        if ($this->adminContextProvider->getContext()?->getEntity()->getInstance()) {
            $labelsIds = $this->adminContextProvider->getContext()->getEntity()->getInstance()->getLabelsIds();
            $labels = $this->choiceRepository->findBy(['id' => $labelsIds]);
            $labels = array_map(fn (Choice $choice) => $choice->getLibelle(), $labels);
            yield TextField::new('labels', 'Labels')
                ->setValue(implode(', ', $labels))
                ->setCustomOption('mapped', false)
                ->hideOnForm()
            ;
        }
        yield CollectionField::new('scores')
            ->setEntryType(ScoreAdminType::class)
            ->setTemplatePath('admin/crud/score_admin.html.twig')
            ->hideOnIndex()
        ;
    }

    public function view(AdminContext $context): Response
    {
        /** @var Reponse|null $object */
        $object = $context->getEntity()->getInstance();

        if (!$object) {
            throw new NotFoundHttpException('Unable to find the reponse');
        }

        $url = $this->router->generate('app_resultat_single', ['uuid' => $object->getUuid()], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->redirect($url);
    }
}
