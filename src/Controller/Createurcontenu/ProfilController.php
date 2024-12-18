<?php

namespace App\Controller\Createurcontenu;

use id;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class ProfilController extends AbstractController
{
    #[Route('/createur-contenu/profil/{id}', name: 'app_createur_contenu_profil')]
    #[IsGranted('ROLE_USER')] // Protéger l'accès à la route
    public function index(int $id): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté

        if (!$user || $user->getId() !== $id) {
            throw $this->createAccessDeniedException('Accès interdit.');
        }

        return $this->render('createurcontenu/profil.html.twig', [
            'user' => $user,
        ]);
    }
}
