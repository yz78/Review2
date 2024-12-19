<?php

namespace App\Controller;

use App\Repository\VideoRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(VideoRepository $videoRepository): Response
    {
        $videos = $videoRepository->findAll();
        $user = $this->getUser();

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'lesVideos' => $videos,
            'user' => $user,
        ]);
    }
}
