<?php

namespace App\Controller;

use app\Entity\Video;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\VideoFormType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class VideoController extends AbstractController
{
    private $em;
    private $videoRepository;
    public function __construct(VideoRepository $videoRepository, EntityManagerInterface $em)
    {
        $this->videoRepository = $videoRepository;
        $this->em = $em;
    }

    #[Route('/video', methods: ['GET'], name: 'app_video')]
    public function index(): Response
    {
        $videos = $this->videoRepository->findAll();
        return $this->render('video/index.html.twig', [
            'controller_name' => 'videoController',
            'videos' => $videos
        ]);
    }

    #[Route('/video/create', name: 'create_video')]
    public function create(Request $request): Response
    {
        $video = new Video();
        $form = $this->createForm(VideoFormType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newvideo = $form->getData();

            $videoPath = $form->get('video')->getData();
            if($videoPath){
                $newFileName = uniqid() . '.' . $videoPath->guessExtension();
                try {
                    $videoPath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }
                $newvideo->setVideo('/uploads/' . $newFileName);
            }
            $user = $this->getUser();
            $newvideo->setUserId($user->getId());
            $newvideo->setPublishDate(new \DateTimeImmutable());
            $newvideo->setUserName($user->getNickname());
            $this->em->persist($newvideo);
            $this->em->flush();

            return $this->redirectToRoute('app_main');
        }


        return $this->render('video/create.html.twig', [
            'controller_name' => 'VideoController',
            'form' => $form->createView()
        ]);
    }

    #[Route('/video/edit/{id}', name: 'edit_video')]
    public function edit(Request $request, $id): Response
    {
        $video = $this->videoRepository->find($id);
        $form = $this->createForm(VideoFormType::class, $video);

        $form->handleRequest($request);
        $videoPath = $form->get('video')->getData();

        if($form->isSubmitted() && $form->isValid()){

            if($videoPath){
                if ($video->getVideo() !== null) {
                    if (file_exists($this->getParameter('kernel.project_dir') . $video->getVideo())) {
                        $this->getParameter('kernel.project_dir') . $video->getVideo();
                    }
                    $newFileName = uniqid() . '.' . $videoPath->guessExtension();

                    try {
                        $videoPath->move(
                            $this->getParameter('kernel.project_dir') . '/public/uploads',
                            $newFileName
                        );
                    } catch (FileException $e) {
                        return new Response($e->getMessage());
                    }

                    $video->setVideo('/uploads/' . $newFileName);
                    $this->em->flush();

                    return $this->redirectToRoute('app_main');
                }
            }else{
                $video->setTitle($form->get('title')->getData());
                $video->setPublishDate(new \DateTimeImmutable());
                $video->setBody($form->get('body')->getData());

                $this->em->flush();

                return $this->redirectToRoute('app_main');

            }
        }

        return $this->render('video/edit.html.twig', [
            'controller_name' => 'VideoController',
            'video' => $video,
            'form' => $form->createView()
        ]);
    }

    #[Route('/video/delete/{id}', methods: ['GET', 'DELETE'], name: 'delete_video')]
    public function delete($id): Response
    {
        $video = $this->videoRepository->find($id);
        $this->em->remove($video);
        $this->em->flush();

        return $this->redirectToRoute('app_main');
    }

    #[Route('/video/{id}', methods: ['GET', 'POST'], name: 'show_video')]
    public function show($id, Request $request, ManagerRegistry $doctrine): Response
    {
        $video = $this->videoRepository->find($id);
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()){
            $newComment = $commentForm->getData();
            $newComment->setvideo($video);
            $newComment->setCreatedAt(new \DateTimeImmutable());
            $newComment->setNickname($this->getUser()->getNickname());

            $parentid = $commentForm->get('parentid')->getData();

            if($parentid != null){
                $parent = $this->em->getRepository(Comment::class)->find($parentid);
            }

            $comment->setParent($parent ?? null);

            $this->em->persist($newComment);
            $this->em->flush();
            $this->addFlash('success', 'Comment added');

            return $this->redirectToRoute('show_video', ['id' => $video->getId()]);
        }

        $video = $this->videoRepository->find($id);
        return $this->render('video/show.html.twig', [
            'controller_name' => 'VideoController',
            'video' => $video,
            'commentForm' => $commentForm->createView()
        ]);
    }
}
