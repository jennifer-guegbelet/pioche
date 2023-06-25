<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Form\EmpruntType;
use App\Repository\EmpruntRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/emprunt')]
class EmpruntController extends AbstractController
{
    #[Route('/', name: 'app_emprunt_index', methods: ['GET'])]
    public function index(EmpruntRepository $empruntRepository): Response
    {
        return $this->render('emprunt/index.html.twig', [
            'emprunts' => $empruntRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_emprunt_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EmpruntRepository $empruntRepository, Livre $livre,EntityManagerInterface $entityManager): Response
    {

        // Vérifiez si le livre existe et est disponible
        if (!$livre || !$livre->isDisponibilite()) {
            $this->addFlash('danger', 'Le livre demandé n\'existe pas ou est indisponible.');
            return $this->redirectToRoute('app_livre_show', ['id' => $livre->getId()]);
        }

        $utilisateur = $this->getUser();
        
        // Vérifier si l'utilisateur a déjà emprunté ce livre
        $existingEmprunt = $entityManager->getRepository(Emprunt::class)->findOneBy([
            'utilisateur' => $utilisateur,
            'livre' => $livre
        ]);

        if ($existingEmprunt) {
            $this->addFlash('danger', 'Vous avez déjà emprunté ce livre.');
            return $this->redirectToRoute('app_livre_show', ['id' => $livre->getId()]);
        }

        // Créez l'entité Emprunt et effectuez les opérations nécessaires

        $emprunt = new Emprunt();
        $emprunt->setLivre($livre);
        $emprunt->setUtilisateur($this->getUser());
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setDateRetourPrevue((new \DateTime())->add(new \DateInterval('P15D')));
        $emprunt->setStatut('En cours');

        $entityManager->persist($emprunt);
        $entityManager->flush();

         $livre->setDisponibilite(false);
            $entityManager->persist($livre);
            $entityManager->flush();

            $this->addFlash('success', 'Livre emprunté avec succès.');

            return $this->redirectToRoute('app_livre_show', ['id' => $livre->getId()]);
    }

    #[Route('/{id}', name: 'app_emprunt_show', methods: ['GET'])]
    public function show(Emprunt $emprunt): Response
    {
        return $this->render('emprunt/show.html.twig', [
            'emprunt' => $emprunt,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_emprunt_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Emprunt $emprunt, EmpruntRepository $empruntRepository): Response
    {
        $form = $this->createForm(EmpruntType::class, $emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $empruntRepository->save($emprunt, true);

            return $this->redirectToRoute('app_emprunt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('emprunt/edit.html.twig', [
            'emprunt' => $emprunt,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_emprunt_delete', methods: ['POST'])]
    public function delete(Request $request, Emprunt $emprunt, EmpruntRepository $empruntRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$emprunt->getId(), $request->request->get('_token'))) {
            $empruntRepository->remove($emprunt, true);
        }

        return $this->redirectToRoute('app_emprunt_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/livre/emprunt/{livreId}", name="emprunt_livre")
     */
    public function emprunterLivre(int $livreId, Request $request,EntityManagerInterface $entityManager): Response
    {
       
        $livre = $entityManager->getRepository(Livre::class)->find($livreId);

        if (!$livre) {
            throw $this->createNotFoundException('Le livre demandé n\'existe pas.');
        }

        $emprunt = new Emprunt();
        $emprunt->setLivre($livre);
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setDateRetourPrevue((new \DateTime())->add(new \DateInterval('P15D')));
        $emprunt->setStatut('En cours');

        $form = $this->createFormBuilder($emprunt)
            ->add('dateRetourPrevue', DateType::class, [
                'widget' => 'single_text',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($emprunt);
            $entityManager->flush();

            return $this->redirectToRoute('livre_details', ['id' => $livreId]);
        }

        return $this->render('livre/emprunt.html.twig', [
            'livre' => $livre,
            'form' => $form->createView(),
        ]);
    }

    
}
