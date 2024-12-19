<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\CategorieFilterType;
use App\Repository\VideoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AllVideoController extends AbstractController
{
    #[Route('/list/allVideo', name: 'app_listeallvideo', methods: ["GET", "POST"])]
    public function listeVideos(VideoRepository $repo, Request $request): Response
    {

        $filterForm = $this->createForm(CategorieFilterType::class);
        $filterForm->handleRequest($request);

        $videos = $repo->findAll();

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $categories = $filterForm->get('categories')->getData();
        
            if (!empty($categories)) {
                // Convertir l'ArrayCollection en tableau d'ID
                $categoryIds = [];
                foreach ($categories as $category) {
                    $categoryIds[] = $category->getId();
                }
        
                $videos = $repo->findByCategories($categoryIds);
            }
        }

        return $this->render('all_video/listeVideos.html.twig', [
            'lesVideos' => $videos,
            'filterForm' => $filterForm->createView(),
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
