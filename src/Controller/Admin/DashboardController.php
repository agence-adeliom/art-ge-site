<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Choice;
use App\Entity\City;
use App\Entity\EasyAdmin\User;
use App\Entity\Epci;
use App\Entity\Question;
use App\Entity\Repondant;
use App\Entity\Reponse;
use App\Entity\Territoire;
use App\Entity\Thematique;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
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
    ) {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->redirect($this->adminUrlGenerator->setController(ReponseCrudController::class)->generateUrl());
    }

    public function configureAssets(): Assets
    {
        $assets = Assets::new();

        if ('staging' == $_ENV['APP_ENV'] && !isset($_ENV['DDEV_TLD'])) {
            $assets->addHtmlContentToBody('<script>window.markerConfig = {project: \'659512dfaf650a0597c940c1\', source: \'snippet\'}; !function(e,r,a){if(!e.__Marker){e.__Marker={};var t=[],n={__cs:t};["show","hide","isVisible","capture","cancelCapture","unload","reload","isExtensionInstalled","setReporter","setCustomData","on","off"].forEach(function(e){n[e]=function(){var r=Array.prototype.slice.call(arguments);r.unshift(e),t.push(r)}}),e.Marker=n;var s=r.createElement("script");s.async=1,s.src="https://edge.marker.io/latest/shim.js";var i=r.getElementsByTagName("script")[0];i.parentNode.insertBefore(s,i)}}(window,document);</script>');
        }

        return $assets;
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

        yield MenuItem::section('Données géographiques');
        yield MenuItem::linkToCrud('Territoires', '', Territoire::class);
        yield MenuItem::linkToCrud('EPCI', '', Epci::class);
        yield MenuItem::linkToCrud('Villes', '', City::class);

        yield MenuItem::section('Super Admin');
        yield MenuItem::linkToCrud('Thématiques', '', Thematique::class);
        yield MenuItem::linkToCrud('Questions', '', Question::class);
        yield MenuItem::linkToCrud('Choix', '', Choice::class);
    }
}
