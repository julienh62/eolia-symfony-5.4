<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordHasherInterface $passwordEncoder)
     {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('jhennebo@gmail.com')
            ->setFullName('julien hennebo')
            ->setPassword(
                $this->passwordEncoder->hashPassword($admin, 'azerty')
            )
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $faker = Faker\Factory::create('fr_FR');

        for ($usr = 1; $usr <= 5; $usr++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setFullName($faker->name())
                ->setPassword(
                    $this->passwordEncoder->hashPassword($user, 'secret')
                );


            $manager->persist($user);
        }


        $manager->flush();
    }
}
