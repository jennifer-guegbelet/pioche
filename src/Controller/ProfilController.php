<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}

