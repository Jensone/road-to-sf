<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ){}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user
                ->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, 'admin'))
                ->setWarningCount($faker->randomElement([0, 1, 2, 3]))
                ->setIsBanned($faker->boolean(80))
                ->setIsActive($faker->boolean(10))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
            ;
            $manager->persist($user);
            $this->addReference('USER_' . $i, $user); // Ajoute l'objet dans un tableau temporaire dédié pendant le chargement des fixtures (KEY => VALUE)
        }

        $manager->flush();
    }
}
