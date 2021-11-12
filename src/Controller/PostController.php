<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(PostRepository $repo): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $repo->findAll(),
        ]);
    }
}
