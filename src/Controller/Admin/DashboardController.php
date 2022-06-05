<?php

namespace App\Controller\Admin;

use App\Entity\ApiUser;
use App\Entity\Document;
use App\Entity\Harmonization;
use App\Entity\Roles;
use App\Entity\TrainingCenters;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @return Response
     */
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('security/dashboard.html.twig', [
            'dashboard_controller_filepath' => (new \ReflectionClass(static::class))->getFileName(),
            'dashboard_controller_class' => (new \ReflectionClass(static::class))->getShortName(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('СПРУТ - admin panel');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Рабочий стол', 'fa fa-home', 'app_main');
//        yield MenuItem::linkToCrud('Согласования', 'fa fa-link', Harmonization::class);
//
        yield MenuItem::section('Пользователи', 'fa fa-user');
        yield MenuItem::linkToCrud('Роли', 'fa fa-user', Roles::class);
        yield MenuItem::linkToCrud('Администраторы', 'fa fa-user', User::class);
//        yield MenuItem::linkToCrud('API пользователи', 'fa fa-user', ApiUser::class);
//
//        yield MenuItem::section('Учебные центры', 'fa fa-university');
//        yield MenuItem::linkToCrud('Учебные центры', 'fa fa-university', TrainingCenters::class);
//        yield MenuItem::linkToCrud('Формат файлов', 'fa fa-file', Document::class);
    }
}
