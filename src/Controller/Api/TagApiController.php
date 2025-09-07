<?php

namespace App\Controller\Api;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tags', name: 'api_tags_')]
class TagApiController extends AbstractController
{
    #[Route('/', name: 'tags_list', methods: ['GET'])]
    public function list(TagRepository $tagRepository): JsonResponse
    {
        $tags = $tagRepository->findAll();

        $data = array_map(fn($tag) => [
            'id' => $tag->getId(),
            'name' => $tag->getTagName(),
        ], $tags);

        return $this->json($data);
    }
}
