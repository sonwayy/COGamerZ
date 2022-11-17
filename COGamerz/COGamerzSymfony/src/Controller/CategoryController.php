<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    private $em;
    private $postRepository;
    private $videoRepository;
    
    public function __construct(PostRepository $postRepository, VideoRepository $videoRepository, EntityManagerInterface $em)
    {
        $this->postRepository = $postRepository;
        $this->videoRepository = $videoRepository;
        $this->em = $em;
        
    }

    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        $posts = $this->postRepository->findAll();
        $videos = $this->videoRepository->findAll();
        // Render the view
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'posts' => $posts,
            'videos' => $videos
        ]);
    }

    // Here we will send posts to all the categories

    #[Route('/category/pow', name: 'app_pow')]
    public function pow(): Response
    {
        // Get all the posts and videos
        $posts = $this->postRepository->findAll();
        $videos = $this->videoRepository->findAll();
        // Render the view
        return $this->render('category/pow.html.twig', [
            'controller_name' => 'CategoryController',
            'posts' => $posts,
            'videos' => $videos
        ]);
    }

    #[Route('/category/wr', name: 'app_wr')]
    public function wr(): Response
    {
        // Get all the posts and videos
        $posts = $this->postRepository->findAll();
        $videos = $this->videoRepository->findAll();
        // Render the view
        return $this->render('category/wr.html.twig', [
            'controller_name' => 'CategoryController',
            'posts' => $posts,
            'videos' => $videos
        ]);
    }

    #[Route('/category/fs', name: 'app_fs')]
    public function fs(): Response
    {
        // Get all the posts and videos
        $posts = $this->postRepository->findAll();
        $videos = $this->videoRepository->findAll();
        // Render the view
        return $this->render('category/fs.html.twig', [
            'controller_name' => 'CategoryController',
            'posts' => $posts,
            'videos' => $videos
        ]);
    }

}
