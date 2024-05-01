<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\User;

use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {

        return $this->render('admin/index.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Articles Hub');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToUrl('Website', 'fa fa-laptop-code', '/');
 
        yield MenuItem::section('Users', 'fas fa-user');
        yield MenuItem::subMenu('Users', 'fas fa-ellipsis')->setSubItems([
            MenuItem::linkToCrud('Create', 'fas fa-plus ', User::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Display', 'fas fa-eye', User::class)
        ]);

        yield MenuItem::section('Articles', 'fas fa-book-open');
        yield MenuItem::subMenu('Article', 'fas fa-ellipsis')->setSubItems([
            MenuItem::linkToCrud('Create', 'fas fa-plus ', Article::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Display', 'fas fa-eye', Article::class)
        ]);

        yield MenuItem::subMenu('Tags', 'fas fa-ellipsis')->setSubItems([
            MenuItem::linkToCrud('Create', 'fas fa-plus ', Tag::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Display', 'fas fa-eye', Tag::class)
        ]);
    }
}
