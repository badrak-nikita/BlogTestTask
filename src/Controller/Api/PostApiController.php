<?php

namespace App\Controller\Api;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/posts', name: 'api_posts_')]
class PostApiController extends AbstractController
{
    #[Route('/', name: 'posts_list', methods: ['GET'])]
    public function list(PostRepository $postRepository, Request $request): JsonResponse
    {
        $tagId = $request->query->get('tag');

        $qb = $postRepository->findByTag($tagId ? (int)$tagId : null);
        $posts = $qb->getQuery()->getResult();

        $data = array_map(fn($post) => [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'tags' => array_map(fn($tag) => ['id' => $tag->getId(), 'name' => $tag->getTagName()], $post->getTags()->toArray()),
            'createdAt' => $post->getCreatedAt()->format(\DateTimeInterface::ATOM),
        ], $posts);

        return $this->json($data);
    }
}
