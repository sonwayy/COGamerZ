<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    private $postRepository;
    private $videoRepository;
    public function __construct(PostRepository $postRepository, VideoRepository $videoRepository)
    {
        // Inject the PostRepository service
        $this->postRepository = $postRepository;
        
        // Inject the VideoRepository service
        $this->videoRepository = $videoRepository;
    }

    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        // Get all the posts
        $posts = $this->postRepository->findAll();
        $videos = $this->videoRepository->findAll();

        // Render the view
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'posts' => $posts,
            'videos' => $videos
        ]);
    }
    
}
