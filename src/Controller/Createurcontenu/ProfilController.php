<?php

namespace App\Controller\Createurcontenu;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/createurcontenu/profil', name: 'app_createurcontenu_profil')]
    public function index(): Response
    {
        return $this->render('createurcontenu/profil.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
}
