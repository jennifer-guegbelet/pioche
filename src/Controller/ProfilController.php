<?php 

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="app_profil_index")
     */
    public function index(): Response
    {
        $utilisateur = $this->getUser();

        return $this->render('profil/index.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }


    /**
     * @Route("/utilisateur/{id}/edit", name="utilisateur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Utilisateur $utilisateur,EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('utilisateur_show', ['id' => $utilisateur->getId()]);
        }

        return $this->render('profil/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

}

