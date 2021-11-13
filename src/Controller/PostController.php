<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostLike;
use App\Repository\PostRepository;
use App\Repository\PostLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(PostRepository $repo): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $repo->findAll(),
        ]);
    }

    /**
     * Allow to like or unlike an article
     *
     * @param App\Entity\Post $post
     * @param Doctrine\ORM\EntityManagerInterface $manager
     * @param App\Repository\PostLikeRepository $likeRepo
     * @return Symfony\Component\HttpFoundation\Response
     */
    #[Route('/post/{id}/like', name: 'post_like')]
    public function like(Post $post, EntityManagerInterface $manager, PostLikeRepository $likeRepo): Response
    {
        $user= $this->getUser();

        if (!$user) return $this->json([
                'code' => 403,
                'message' => 'Aucun utilisateur connecté'
            ], 403);

        if ($post->isLikedByUser($user)) {
            $like= $likeRepo->findOneBy([
                'post' => $post,
                'user' => $user
            ]);

            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like bien supprimé',
                'nombre de likes' => $likeRepo->count(['post' => $post])
            ], 200);
        }

        $like= new PostLike;
        $like->setPost($post)
            ->setUser($user);

        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Like bien ajouté',
            'nombre de likes' => $likeRepo->count(['post' => $post])
        ], 200);
    }
}
