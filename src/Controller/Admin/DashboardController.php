<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Exemplaire;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use App\Entity\Reservation;
use App\Entity\Auteur;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('@EasyAdmin/page/content.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Pioche');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
    
        yield MenuItem::section('Bibliothèque');
        yield MenuItem::linkToCrud('Exemplaires', 'fa fa-book', Exemplaire::class);
        yield MenuItem::linkToCrud('Genres', 'fa fa-tags', Genre::class);
        yield MenuItem::linkToCrud('Livres', 'fa fa-book', Livre::class);
    
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', Utilisateur::class);
    
        yield MenuItem::section('Réservations');
        yield MenuItem::linkToCrud('Réservations', 'fa fa-calendar', Reservation::class);
    
        yield MenuItem::section('Auteurs');
        yield MenuItem::linkToCrud('Auteurs', 'fa fa-user', Auteur::class);
    }
}
