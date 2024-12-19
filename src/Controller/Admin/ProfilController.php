<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_profile')]
    public function showProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur connecté via le service Symfony Security
        $user = $this->getUser();

        // Vérifier que l'utilisateur est connecté
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour voir cette page.');
        }

        // Optionnel: Si vous avez des données supplémentaires à récupérer depuis la base de données, faites-le ici.
        // Par exemple : $userData = $entityManager->getRepository(User::class)->find($user->getId());

        // Passer l'utilisateur à la vue
        return $this->render('admin/profil/show.html.twig', [
            'user' => $user,
        ]);
    }

    

/**
 * Permet à l'utilisateur de modifier son profil
 * 
 * @Route("/profile/edit", name="app_profile_edit")
 */
public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
{
    // Récupérer l'utilisateur connecté
    $user = $this->getUser();

    if (!$user) {
        throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
    }

    // Créer le formulaire pour modifier les informations de profil
    $form = $this->createForm(ProfileType::class, $user);

    // Traiter la soumission du formulaire
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gérer la photo si l'utilisateur en a téléchargé une
        $file = $form->get('photo')->getData();
        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = uniqid() . '.' . $file->guessExtension();

            // Déplacer le fichier téléchargé
            try {
                $file->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/photos', 
                    $newFilename
                );
                $user->setPhoto($newFilename);
            } catch (\Exception $e) {
                // Gérer l'exception si l'upload échoue
            }
        }

        // Sauvegarder les changements en base de données
        $entityManager->flush();

        // Rediriger vers la page de profil avec un message de succès
        $this->addFlash('success', 'Profil mis à jour avec succès.');
        return $this->redirectToRoute('app_profile');
    }

    return $this->render('/adminprofile/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}

}