<?php

namespace App\Controller;

use App\Entity\Commentaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/comment')]
class CommentLikeController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('/{id}/like', name: 'comment_like', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function like(Commentaire $comment): JsonResponse
    {
        $user = $this->getUser();
        
        if ($comment->getDislikeCom()->contains($user)) {
            $comment->removeDislikeCom($user);
            $comment->setDislikesCount($comment->getDislikesCount() - 1);
        }

        if (!$comment->getLikeCom()->contains($user)) {
            $comment->addLikeCom($user);
            $comment->setLikesCount($comment->getLikesCount() + 1);
        } else {
            $comment->removeLikeCom($user);
            $comment->setLikesCount($comment->getLikesCount() - 1);
        }

        $this->entityManager->flush();

        return $this->json([
            'likes' => $comment->getLikesCount(),
            'dislikes' => $comment->getDislikesCount(),
            'isLiked' => $comment->getLikeCom()->contains($user),
            'isDisliked' => $comment->getDislikeCom()->contains($user),
        ]);
    }

    #[Route('/{id}/dislike', name: 'comment_dislike', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function dislike(Commentaire $comment): JsonResponse
    {
        $user = $this->getUser();

        if ($comment->getLikeCom()->contains($user)) {
            $comment->removeLikeCom($user);
            $comment->setLikesCount($comment->getLikesCount() - 1);
        }

        if (!$comment->getDislikeCom()->contains($user)) {
            $comment->addDislikeCom($user);
            $comment->setDislikesCount($comment->getDislikesCount() + 1);
        } else {
            $comment->removeDislikeCom($user);
            $comment->setDislikesCount($comment->getDislikesCount() - 1);
        }

        $this->entityManager->flush();

        return $this->json([
            'likes' => $comment->getLikesCount(),
            'dislikes' => $comment->getDislikesCount(),
            'isLiked' => $comment->getLikeCom()->contains($user),
            'isDisliked' => $comment->getDislikeCom()->contains($user),
        ]);
    }
}
