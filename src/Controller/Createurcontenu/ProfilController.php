<?php

namespace App\Controller\Createurcontenu;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class ProfilController extends AbstractController
{
    #[Route('/createur-contenu/profil/{id}', name: 'app_createur_contenu_profil')]
    #[IsGranted('ROLE_USER')]
    public function index(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user || $user->getId() !== $id) {
            throw $this->createAccessDeniedException('Accès interdit.');
        }

        // Formulaire pré-rempli avec les données de l'utilisateur
        $form = $this->createFormBuilder($user)
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail',
                'attr' => ['class' => 'form-control']
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control']
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'form-control']
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'attr' => ['class' => 'form-control']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ])
            ->getForm();

        // Gestion du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès.');

            return $this->redirectToRoute('app_createur_contenu_profil', ['id' => $id]);
        }

        return $this->render('createurcontenu/profil.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
