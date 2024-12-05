<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    #[Route('/admin/profil', name: 'app_admin_profil')]
    public function index(): Response
    { $user = $this->getDoctrine()->getRepository(User::class)->find(1);

        // Si l'utilisateur n'est pas trouvé, vous pouvez choisir de renvoyer une page d'erreur
        if (!$user) {
            throw $this->createNotFoundException('Aucun utilisateur trouvé');
        } $this->render('admin/profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
}
