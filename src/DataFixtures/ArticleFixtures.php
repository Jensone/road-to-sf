<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {}
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Admin
        $admin = new User();
        $admin
            ->setEmail('admin@admin.fr')
            ->setUsername('admin')
            ->setPassword($this->hasher->hashPassword($admin, 'admin'))
            ->setWarningCount(0)
            ->setRoles(['ROLE_ADMIN'])
        ;

        $manager->persist($admin);
        $manager->flush(); // Admin enregistré en base de données

        $categories = [
            'Front-end',
            'Back-end',
            'DevOps',
            'Cloud'
        ];

        $catArray = [];
        foreach ($categories as $category) {
            $cat = new Category();
            $cat->setName($category);

            $manager->persist($cat);
            array_push($catArray, $cat);
        }

        $manager->flush();

        // Articles
        for ($i = 0; $i < 300; $i++) {
            $article = new Article();
            $article
                ->setTitle($faker->sentence())
                ->setSlug($faker->slug())
                ->setKeywords($faker->words(5, true))
                ->setDescription($faker->sentence())
                ->setContent($faker->text())
                ->setIsPublished($faker->boolean(70))
                ->setIsArchived($faker->boolean(10))
                ->setAuthor($admin)
                ->setCategory($faker->randomElement($catArray))
            ;

            $manager->persist($article);
            $this->addReference('ARTICLE_' . $i, $article);

            if ($i % 100 === 0) {
                $manager->flush(); // Article enregistré en base de données tous les 10 articles
            }
        }


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
