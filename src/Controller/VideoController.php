<?php

namespace App\Controller;

use App\Form\VideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Video;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VideoController extends AbstractController
{
    #[Route('/list/Video', name: 'app_listevideo', methods: ["GET"])]
    public function listeVideos(VideoRepository $repo): Response
    {
        $videos = $repo->findAll();

        return $this->render('video/listeVideos.html.twig', [
            'lesVideos' => $videos,
        ]);
    }

    #[Route('/video', name: 'app_video', methods: ['GET', 'POST'])]
    #[Route('/video/modif/{id}', name: 'app_video_modif', methods: ['GET', 'POST'])]
    public function ajoutModifVideo(
        Video $video = null, 
        Request $request, 
        EntityManagerInterface $manager
    ): Response {
        $isNew = $video === null;

        if ($isNew) {
            $video = new Video();
            $mode = "ajoutée";
        } else {
            $mode = "modifiée";
        }

        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Extraire l'ID YouTube depuis l'URL
            $url = $video->getLien();
            preg_match(
                '/(?:https?:\/\/(?:www\.)?youtube\.com\/(?:[^\/]+\/\S+|(?:v|e(?:mbed)?)\/([^"&?\/\s]{11})))|(?:https?:\/\/(?:www\.)?youtu\.be\/([^"&?\/\s?]{11}))/',
                $url,
                $matches
            );
            
            $youtubeId = $matches[2] ?? null;
            
            if ($youtubeId === null) {
                $this->addFlash("danger", "L'URL YouTube est invalide !");
                return $this->redirectToRoute($isNew ? 'app_video' : 'app_video_modif', ['id' => $video->getId()]);
            }

            $video->setYoutubeId($youtubeId);

            $manager->persist($video);
            $manager->flush();

            $this->addFlash("success", "La vidéo a bien été $mode !");
            return $this->redirectToRoute('app_listevideo');
        }

        return $this->render('video/ajoutModifVideo.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/video/suppression/{id}', name: 'app_video_suppr', methods: ["GET"])]
    public function SuppressionVideo(Video $video, EntityManagerInterface $manager): Response
    {
        if ($video) {
            $manager->remove($video);
            $manager->flush();
            $this->addFlash("success", "La vidéo a bien été supprimée !");
        }

        return $this->redirectToRoute('app_listevideo');
    }

    #[Route('/video/{id}', name: 'ficheVideo', methods:("GET"))]
    public function ficheVideo(Video $video): Response
    {
        return $this->render('video/ficheVideo.html.twig', [
            'laVideo' => $video
        ]);
    }
}
