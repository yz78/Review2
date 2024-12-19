<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CommentController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/video/{id}/comment', name: 'video_comment_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function addComment(
        Request $request, 
        Video $video
    ): Response {
        // Vérifier que l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour commenter.');
            return $this->redirectToRoute('app_login');
        }

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
        $comment->addPosteCom($user);
        $comment->setVideo($video);
        $comment->setLikesCount(0);
        $comment->setDislikesCount(0);
        
        // Persister le commentaire
        try {
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été publié avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de la publication du commentaire.');
        }

        // Rediriger vers la page de la vidéo
        return $this->redirectToRoute('allficheVideo', ['id' => $video->getId()]);
    }

    #[Route('/video/{id}/comments', name: 'video_comments_list', methods: ['GET'])]
    public function listComments(Video $video): Response
    {
        // Récupérer les commentaires triés par date de publication
        $comments = $this->entityManager
            ->getRepository(Commentaire::class)
            ->findBy(
                ['video' => $video], 
                ['datePublication' => 'DESC']
            );

        return $this->render('all_video/_comments.html.twig', [
            'video' => $video,
            'comments' => $comments
        ]);
    }

    #[Route('/comment/{id}/like', name: 'comment_like', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function likeComment(Commentaire $comment): Response
    {
        $user = $this->getUser();
        
        try {
            if ($comment->isLikedByUser($user)) {
                // Si déjà liké, on retire simplement le like
                $comment->removeLikeCom($user);
            } else {
                // Si disliké, on retire d'abord le dislike
                if ($comment->isDislikedByUser($user)) {
                    $comment->removeDislikeCom($user);
                }
                // Puis on ajoute le like
                $comment->addLikeCom($user);
            }
            
            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'likes' => $comment->getLikesCount(),
                'dislikes' => $comment->getDislikesCount(),
                'hasLiked' => $comment->isLikedByUser($user),
                'hasDisliked' => $comment->isDislikedByUser($user)
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Une erreur est survenue lors du like'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/comment/{id}/dislike', name: 'comment_dislike', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function dislikeComment(Commentaire $comment): Response
    {
        $user = $this->getUser();
        
        try {
            if ($comment->isDislikedByUser($user)) {
                // Si déjà disliké, on retire simplement le dislike
                $comment->removeDislikeCom($user);
            } else {
                // Si liké, on retire d'abord le like
                if ($comment->isLikedByUser($user)) {
                    $comment->removeLikeCom($user);
                }
                // Puis on ajoute le dislike
                $comment->addDislikeCom($user);
            }
            
            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'likes' => $comment->getLikesCount(),
                'dislikes' => $comment->getDislikesCount(),
                'hasLiked' => $comment->isLikedByUser($user),
                'hasDisliked' => $comment->isDislikedByUser($user)
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Une erreur est survenue lors du dislike'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/comment/{id}/delete', name: 'comment_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteComment(Commentaire $comment): Response
    {
        try {
            $this->entityManager->remove($comment);
            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Commentaire supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Une erreur est survenue lors de la suppression du commentaire'
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/comment/add', name: 'comment_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function addCommentNew(Request $request): Response
    {
        $content = $request->request->get('content');
        $videoId = $request->request->get('videoId');

        if (!$content || !$videoId) {
            return $this->json([
                'success' => false,
                'error' => 'Contenu du commentaire ou ID de la vidéo manquant'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $video = $this->entityManager->getRepository(Video::class)->find($videoId);
            if (!$video) {
                throw new \Exception('Vidéo non trouvée');
            }

            $comment = new Commentaire();
            $comment->setContenu($content);
            $comment->addPosteCom($this->getUser());
            $comment->setDatePublication(new \DateTime());
            $comment->setLikesCount(0);
            $comment->setDislikesCount(0);
            
            $video->addCommentaire($comment);
            
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->json([
                'success' => true,
                'message' => 'Commentaire ajouté avec succès'
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => 'Une erreur est survenue lors de l\'ajout du commentaire'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
