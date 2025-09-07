<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tags = [];
        $tagNames = ['Новини', 'Гумор', 'Icторiя', 'Кулiнарiя', 'Котики'];

        foreach ($tagNames as $name) {
            $tag = new Tag();
            $tag->setTagName($name);
            $manager->persist($tag);
            $tags[] = $tag;
        }

        for ($i = 1; $i <= 5; $i++) {
            $post = new Post();
            $post->setTitle("Тестовий пост №$i");
            $post->setContent("Текст посту бла бла бла №$i");

            $assignedTags = array_rand($tags, rand(1, 2));
            if (!is_array($assignedTags)) {
                $assignedTags = [$assignedTags];
            }
            foreach ($assignedTags as $tagIndex) {
                $post->addTag($tags[$tagIndex]);
            }

            $manager->persist($post);
        }

        $manager->flush();
    }
}
