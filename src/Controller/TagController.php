<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tags')]
class TagController extends AbstractController
{
    #[Route('/', name: 'tag_index', methods: ['GET'])]
    public function index(TagRepository $tagRepository): Response
    {
        $tags = $tagRepository->findAll();

        return $this->render('tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    #[Route('/create', name: 'tag_new', methods: ['GET','POST'])]
    public function createTag(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $tagName = trim($request->request->get('tagName'));

            if ($tagName) {
                $tag = new Tag();
                $tag->setTagName($tagName);

                $em->persist($tag);
                $em->flush();

                return $this->redirectToRoute('tag_index');
            } else {
                $error = 'Назва тегу не може бути порожньою';
            }
        }

        return $this->render('tag/create.html.twig', [
            'error' => $error ?? null,
        ]);
    }
}
