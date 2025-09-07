<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Service\PostService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/posts')]
class PostController extends AbstractController
{
    #[Route('/', name: 'post_index', methods: ['GET'])]
    public function index(
        PostRepository $postRepository,
        TagRepository $tagRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        $tagId = $request->query->get('tag');

        $queryBuilder = $postRepository->findByTag($tagId ? (int)$tagId : null);

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('post/index.html.twig', [
            'posts' => $pagination,
            'tags' => $tagRepository->findAll(),
            'currentTag' => $tagId,
        ]);
    }

    #[Route('/create', name: 'post_create', methods: ['GET', 'POST'])]
    public function createPost(Request $request, TagRepository $tagRepository, PostService $postService): Response
    {
        $tags = $tagRepository->findAll();
        $error = null;

        if ($request->isMethod('POST')) {
            $title = trim((string) $request->request->get('title'));
            $content = trim((string) $request->request->get('content'));
            $tagIds = $request->request->all('tags') ?? [];

            if (!$title || !$content) {
                $error = 'Заголовок та контент не можуть бути порожніми';
            } else {
                $postService->createPost($title, $content, $tagIds);

                return $this->redirectToRoute('post_index');
            }
        }

        return $this->render('post/create.html.twig', [
            'tags' => $tags,
            'error' => $error,
        ]);
    }

    #[Route('/{id}', name: 'post_show', methods: ['GET'])]
    public function showPost(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/{id}/edit', name: 'post_edit', methods: ['GET','POST'])]
    public function editPost(Post $post, Request $request, TagRepository $tagRepository, PostService $postService): Response
    {
        $tags = $tagRepository->findAll();
        $error = null;

        if ($request->isMethod('POST')) {
            $title = trim((string) $request->request->get('title'));
            $content = trim((string) $request->request->get('content'));
            $tagIds = $request->request->all('tags') ?? [];

            if (!$title || !$content) {
                $error = 'Заголовок та контент не можуть бути порожніми';
            } else {
                $postService->updatePost($post, $title, $content, $tagIds);

                $this->addFlash('success', 'Пост успішно оновлено!');
                return $this->redirectToRoute('post_index');
            }
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'tags' => $tags,
            'error' => $error,
        ]);
    }

    #[Route('/{id}/delete', name: 'post_delete', methods: ['POST'])]
    public function deletePost(Post $post, EntityManagerInterface $em): Response
    {
        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('post_index');
    }
}
