<?php 
// src/Controller/ReservationController.php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Livre;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/new/{id}', name: 'reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository, Livre $livre,EntityManagerInterface $entityManager): Response
    {

        // Vérifiez si le livre existe et est disponible
        if (!$livre || !$livre->isDisponibilite()) {
            $this->addFlash('danger', 'Le livre demandé n\'existe pas ou est indisponible.');
            return $this->redirectToRoute('app_livre_show', ['id' => $livre->getId()]);
        }

        $utilisateur = $this->getUser();
        
        // Vérifier si l'utilisateur a déjà reservé ce livre
        $existingReservation = $entityManager->getRepository(Reservation::class)->findOneBy([
            'utilisateur' => $utilisateur,
            'livre' => $livre
        ]);

        if ($existingReservation) {
            $this->addFlash('danger', 'Vous avez déjà reservé ce livre.');
            return $this->redirectToRoute('app_livre_show', ['id' => $livre->getId()]);
        }

        // Créez l'entité Reservation et effectuez les opérations nécessaires

        $reservation = new Reservation();
        $reservation->setLivre($livre);
        $reservation->setUtilisateur($this->getUser());
        $reservation->setDateReservation(new \DateTime());
        $reservation->setActif(1);
        

        $entityManager->persist($reservation);
        $entityManager->flush();

         $livre->setDisponibilite(false);
            $entityManager->persist($livre);
            $entityManager->flush();

            $this->addFlash('success', 'Livre réservé avec succès.');

            return $this->redirectToRoute('app_livre_show', ['id' => $livre->getId()]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }
}
