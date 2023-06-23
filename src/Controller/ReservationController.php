<?php 
// src/Controller/ReservationController.php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Livre;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation/new/{id}", name="reservation_new", methods={"GET", "POST"})
     */
    public function new(EntityManagerInterface $entityManager,Request $request, Livre $livre): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder la réservation dans la base de données
            $entityManager->persist($reservation);
            $entityManager->flush();

            // Rediriger vers une autre page, par exemple, la liste des réservations
            return $this->redirectToRoute('Livre_list');
        }

        return $this->render('Livre/show.html.twig', [
            'form' => $form->createView(),
            'livre' => $livre,
        ]);
    }
}
