<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Comment;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\ArticleFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Récupération des utilisateurs nouvellement créés
        $users = [];
        for ($i = 0; $i < 70; $i++) {
            $users[] = $this->getReference('USER_' . $i, User::class);
        }

        // Récupération des articles nouvellement créés
        $articles = [];
        for ($i = 0; $i < 100; $i++) {
            $articles[] = $this->getReference('ARTICLE_' . $i, Article::class);
        }

        // TODO: Régidiger la création de commentaires pour les articles

        foreach ($articles as $item) {
            $count = $faker->numberBetween(1, 5); // Choisir en 1 et 2

            for ($i = 0; $i < $count; $i++) { // Pour le nombre choisi
                $comment = new Comment();
                $comment
                    ->setAuthor($faker->randomElement($users))
                    ->setContent($faker->text())
                    ->setIsModerated($faker->boolean(90))
                    ->setIsPublished($faker->boolean(90))
                    ->setArticle($item)
                ;

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ArticleFixtures::class, 
        ];
    }
}
