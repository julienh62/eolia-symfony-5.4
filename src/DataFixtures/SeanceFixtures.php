<?php

namespace App\DataFixtures;

use App\Entity\Seance;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SeanceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

       
        for($sean = 1; $sean <= 5; $sean++){
            $seance = new Seance();
            $seance->setName($faker->randomElement($array = array ('Char à voile','Catamaran','Char à voile kids')));
            //$seance->setStock($faker->numberBetween($min = 5, $max = 8));
            $seance->setPrice('60');
            $seance->setStock('12');
            $seance->setDatedelaseance($faker->dateTimeInInterval('0 week', '+10 days'));
            
            $manager->persist($seance);
        }
        $manager->flush();
    }
}
