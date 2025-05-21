<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::class;

        // Récupération des utilisateurs nouvellement créés
        $users = [];
        for ($i=0; $i < 100; $i++) { 
            $users[] = $this->getReference('USER_' . $i, User::class);
        }
        
        // TODO: Régidiger les création de commentaires pour les articles

        $manager->flush();
    }
}
