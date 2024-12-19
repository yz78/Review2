<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CommentaireController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/video/{id}/comments', name: 'video_comments_list')]
    public function listComments(Video $video): Response
    {
        $comments = $video->getCommentaires()->toArray();
        
        // Trier les commentaires par date de publication décroissante
        usort($comments, function($a, $b) {
            return $b->getDatePublication() <=> $a->getDatePublication();
        });
        
        return $this->render('all_video/_comments.html.twig', [
            'comments' => $comments,
            'video' => $video
        ]);
    }

    #[Route('/video/{id}/comment', name: 'video_comment_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function addComment(Request $request, Video $video): Response
    {
        // Récupérer le contenu du commentaire
        $content = $request->request->get('content');
        
        // Validation du contenu
        if (empty(trim($content))) {
            $this->addFlash('error', 'Le commentaire ne peut pas être vide.');
            return $this->redirectToRoute('allficheVideo', ['id' => $video->getId()]);
        }

        // Créer le nouveau commentaire
        $comment = new Commentaire();
        $comment->setContenu($content);
        $comment->setDatePublication(new \DateTime());
        $comment->addPosteCom($this->getUser());
        $comment->setVideo($video);
        $comment->setLikesCount(0);
        $comment->setDislikesCount(0);

        // Persister le commentaire
        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        $this->addFlash('success', 'Votre commentaire a été ajouté.');
        return $this->redirectToRoute('allficheVideo', ['id' => $video->getId()]);
    }

    #[Route('/commentaire/{id}/like', name: 'commentaire_like', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function like(Commentaire $commentaire): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        
        // Si l'utilisateur a déjà disliké, on retire le dislike
        if ($commentaire->getDislikeCom()->contains($user)) {
            $commentaire->getDislikeCom()->removeElement($user);
            $user->getDislikeCommentaires()->removeElement($commentaire);
            $commentaire->setDislikesCount($commentaire->getDislikesCount() - 1);
        }

        // Toggle du like
        if ($commentaire->getLikeCom()->contains($user)) {
            // Retirer le like
            $commentaire->getLikeCom()->removeElement($user);
            $user->getLikecommentaires()->removeElement($commentaire);
            $commentaire->setLikesCount($commentaire->getLikesCount() - 1);
            $isLiked = false;
        } else {
            // Ajouter le like
            $commentaire->getLikeCom()->add($user);
            $user->getLikecommentaires()->add($commentaire);
            $commentaire->setLikesCount($commentaire->getLikesCount() + 1);
            $isLiked = true;
        }

        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'likes' => $commentaire->getLikesCount(),
            'dislikes' => $commentaire->getDislikesCount(),
            'isLiked' => $isLiked,
            'isDisliked' => false
        ]);
    }

    #[Route('/commentaire/{id}/dislike', name: 'commentaire_dislike', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function dislike(Commentaire $commentaire): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        // Si l'utilisateur a déjà liké, on retire le like
        if ($commentaire->getLikeCom()->contains($user)) {
            $commentaire->getLikeCom()->removeElement($user);
            $user->getLikecommentaires()->removeElement($commentaire);
            $commentaire->setLikesCount($commentaire->getLikesCount() - 1);
        }

        // Toggle du dislike
        if ($commentaire->getDislikeCom()->contains($user)) {
            // Retirer le dislike
            $commentaire->getDislikeCom()->removeElement($user);
            $user->getDislikeCommentaires()->removeElement($commentaire);
            $commentaire->setDislikesCount($commentaire->getDislikesCount() - 1);
            $isDisliked = false;
        } else {
            // Ajouter le dislike
            $commentaire->getDislikeCom()->add($user);
            $user->getDislikeCommentaires()->add($commentaire);
            $commentaire->setDislikesCount($commentaire->getDislikesCount() + 1);
            $isDisliked = true;
        }

        $this->entityManager->flush();

        return $this->json([
            'success' => true,
            'likes' => $commentaire->getLikesCount(),
            'dislikes' => $commentaire->getDislikesCount(),
            'isLiked' => false,
            'isDisliked' => $isDisliked
        ]);
    }
}
