<?php

namespace App\Controller\Admin;

use App\Entity\user;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct( private AdminUrlGenerator $adminUrlGenerator)
    {
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(userCrudController::class)
            ->generateUrl();

            return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Eco Formation');
    }

    public function configureMenuItems(): iterable
    {
        return[
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::section('user'),
            MenuItem::linkToCrud('user', 'fa fa-user', user::class),
        ];
    }
}
