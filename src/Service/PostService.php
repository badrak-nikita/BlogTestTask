<?php

namespace App\Service;

use App\Entity\Post;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

class PostService
{
    private EntityManagerInterface $em;
    private TagRepository $tagRepository;

    public function __construct(EntityManagerInterface $em, TagRepository $tagRepository)
    {
        $this->em = $em;
        $this->tagRepository = $tagRepository;
    }

    public function createPost(string $title, string $content, array $tagIds = []): Post
    {
        $post = new Post();
        $post->setTitle($title)
            ->setContent($content)
            ->setCreatedAt(new \DateTimeImmutable());

        $this->assignTags($post, $tagIds);

        $this->em->persist($post);
        $this->em->flush();

        return $post;
    }

    public function updatePost(Post $post, string $title, string $content, array $tagIds = []): Post
    {
        $post->setTitle($title)
            ->setContent($content);

        $this->assignTags($post, $tagIds, true);

        $this->em->flush();

        return $post;
    }

    /**
     *
     * @param Post $post
     * @param array<int> $tagIds
     * @param bool $clearExisting
     */
    private function assignTags(Post $post, array $tagIds, bool $clearExisting = false): void
    {
        if ($clearExisting) {
            foreach ($post->getTags() as $existingTag) {
                $post->removeTag($existingTag);
            }
        }

        foreach ($tagIds as $tagId) {
            $tag = $this->tagRepository->find($tagId);
            if ($tag) {
                $post->addTag($tag);
            }
        }
    }
}
