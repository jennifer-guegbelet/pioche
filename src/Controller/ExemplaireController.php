<?php

namespace App\Controller;

use App\Entity\Exemplaire;
use App\Form\ExemplaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/exemplaire')]
class ExemplaireController extends AbstractController
{
    #[Route('/', name: 'app_exemplaire_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $exemplaires = $entityManager
            ->getRepository(Exemplaire::class)
            ->findAll();

        return $this->render('exemplaire/index.html.twig', [
            'exemplaires' => $exemplaires,
        ]);
    }

    #[Route('/new', name: 'app_exemplaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $exemplaire = new Exemplaire();
        $form = $this->createForm(ExemplaireType::class, $exemplaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($exemplaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_exemplaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('exemplaire/new.html.twig', [
            'exemplaire' => $exemplaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_exemplaire_show', methods: ['GET'])]
    public function show(Exemplaire $exemplaire): Response
    {
        return $this->render('exemplaire/show.html.twig', [
            'exemplaire' => $exemplaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_exemplaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Exemplaire $exemplaire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExemplaireType::class, $exemplaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_exemplaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('exemplaire/edit.html.twig', [
            'exemplaire' => $exemplaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_exemplaire_delete', methods: ['POST'])]
    public function delete(Request $request, Exemplaire $exemplaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exemplaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($exemplaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_exemplaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
