<?php

namespace App\Controller;

use App\Entity\Video;
use App\Repository\VideoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AllVideoController extends AbstractController
{
    #[Route('/list/allVideo', name: 'app_listeallvideo', methods: ["GET"])]
    public function listeVideos(VideoRepository $repo): Response
    {
        $videos = $repo->findAll();

        return $this->render('all_video/listeVideos.html.twig', [
            'lesVideos' => $videos,
        ]);
    }

    #[Route('/allvideo/{id}', name: 'allficheVideo', methods:("GET"))]
    public function ficheVideo(Video $video, VideoRepository $repo): Response
    {
        $videos = $repo->findBy([], ['datePublication' => 'DESC']);

        return $this->render('all_video/ficheVideo.html.twig', [
            'laVideo' => $video,
            'lesVideos' => $videos,
        ]);
    }

}
