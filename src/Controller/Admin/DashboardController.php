<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Choice;
use App\Entity\EasyAdmin\User;
use App\Entity\Question;
use App\Entity\Repondant;
use App\Entity\Reponse;
use App\Entity\Thematique;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly AdminUrlGenerator $adminUrlGenerator,
    ) {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->redirect($this->adminUrlGenerator->setController(ReponseCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        if (isset($_ENV['ADMIN_URL']) && 'art-grand-est.ddev.site' === $_ENV['ADMIN_URL']) {
            $title = 'ART-GE Local';
        } else {
            $title = match ($_ENV['APP_ENV']) {
                'dev' => 'ART-GE Dev',
                default => 'ART-GE'
            };
        }

        return Dashboard::new()
            ->disableDarkMode()
            ->setTitle($title)
        ;
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        $userMenu = parent::configureUserMenu($user);
        if ($user instanceof User) {
            $userMenu->setName($user->getFullname())
                ->setGravatarEmail($user->getEmail() ?? '')
                ->addMenuItems([
                    MenuItem::linkToCrud('easy_admin_user.my_profile', 'fa fa-id-card', User::class)->setAction(Action::DETAIL)->setEntityId($user->getId()),
                ])
            ;
        }

        return $userMenu;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToUrl('Accéder au site', 'fa fa-eye', '/');

        yield MenuItem::section('Formulaire');
        yield MenuItem::linkToCrud('Répondants', '', Repondant::class);
        yield MenuItem::linkToCrud('Réponses', '', Reponse::class);

        yield MenuItem::section('Super Admin')->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkToCrud('Thématiques', '', Thematique::class)->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkToCrud('Questions', '', Question::class)->setPermission('ROLE_SUPER_ADMIN');
        yield MenuItem::linkToCrud('Choix', '', Choice::class)->setPermission('ROLE_SUPER_ADMIN');
    }
}
