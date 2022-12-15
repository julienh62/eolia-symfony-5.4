<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Seance;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
 
class SeanceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
     
        $faker = Faker\Factory::create('fr_FR');

       // les 3 categories
         for($cat = 1; $cat <= 5; $cat++){
            $categorie = new Categorie();
            $categorie->setTitle($faker->randomElement($array = array ('Char à voile','Catamaran','Char à voile kids')));
            //$category->setStock($faker->numberBetween($min = 5, $max = 8));
            $categorie->setDescription($faker->sentence());
            
            
           $manager->persist($categorie);
        }




        for($sean = 1; $sean <= 5; $sean++){
            $seance = new Seance();
            //$seance->setName($faker->randomElement($array = array ('Char à voile','Catamaran','Char à voile kids')));
            //$seance->setStock($faker->numberBetween($min = 5, $max = 8));
            $seance->setPrice('50');
            $seance->setStock('12');
            $seance->setDatedelaseance($faker->dateTimeInInterval('0 week', '+10 days'));
            $seance->setCategorie($categorie);

            $manager->persist($seance);
        }
           
        $manager->flush();
    }
}
