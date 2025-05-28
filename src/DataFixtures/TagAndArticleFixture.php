<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class TagAndArticleFixture extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [UserFixture::class];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $users = $manager->getRepository(User::class)->findAll();
        if (empty($users)) {
            throw new \Exception('No users found. Please load UserFixture first.');
        }

        // tags
        $tags = [];
        for ($i = 0; $i < 5; $i++) {
            $tag = new Tag();
            $tag->setTitle(ucfirst($faker->word()));
            $manager->persist($tag);
            $tags[] = $tag;
        }

        // articles
        for ($i = 0; $i < 5; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence(4));
            $article->setBody($faker->paragraph(3));
            $article->setPublished(true);
            $article->setOwner($faker->randomElement($users));

            foreach ($faker->randomElements($tags, rand(1, 3)) as $tag) {
                $article->addTag($tag);
            }

            $manager->persist($article);
        }

        $manager->flush();
    }
}