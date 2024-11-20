<?php

namespace App\Controller;

use App\Form\VideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Video; // Si vous utilisez une entité Video.
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VideoController extends AbstractController
{
    // #[Route('/video', name: 'app_video')]
    // public function ajouterVideo(Request $request): Response
    // {
    //     $video = new Video(); 
    //     $form = $this->createForm(VideoType::class, $video);

    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // Si le formulaire est soumis et valide, persistez les données
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($video);
    //         $entityManager->flush();

    //         $this->addFlash('success', 'Vous avez enregistré la vidéo avec succés !');

    //         return $this->redirectToRoute('app_accueil');
    //     }

    //     return $this->render('video/ajoutVideo.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }

    #[Route('/list/Video', name: 'app_listevideo', methods:("GET"))]
    public function listeVideos(VideoRepository $repo, Request $request): Response
    {
        $videos = $repo->findAll();
    
        return $this->render('video/listeVideos.html.twig', [
            'lesVideos' => $videos
        ]);
    }




    #[Route('/video', name: 'app_video', methods:['GET','POST'])]
    #[Route('/video/modif/{id}', name: 'app_video_modif', methods:['GET','POST'])]
    public function ajoutModifVideo(Video $video=null, VideoRepository $repo, Request $request, EntityManagerInterface $manager): Response
    {
        if($video == null){
            $video=new Video();
            $mode="ajouté";
        } else{
            $mode="modifié";    
        }     
        $form=$this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($video);
            $manager->flush();
            $this->addFlash("success", "La vidéo a bien été $mode !");
            return $this->redirectToRoute('app_listevideo');
        }

        return $this->render('video/ajoutModifVideo.html.twig', [
            'form' => $form->createView()
        ]);

    }

    #[Route('/video/suppression/{id}', name: 'app_video_suppr', methods:("GET"))]
    public function SuppressionAlbum(Video $video=null, VideoRepository $repo, Request $request, EntityManagerInterface $manager): Response
    {
        
            $manager->remove($video);   
            $manager->flush();
            $this->addFlash("success", "La vidéo à bien été supprimé !");
      
        return $this->redirectToRoute('app_listevideo');

    
    }

}
