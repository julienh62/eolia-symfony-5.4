<?php

namespace App\DataFixtures;

use App\Entity\Purchase;
use App\Entity\User;
use App\Entity\Categorie;
use App\Entity\Seance;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class SeanceFixtures extends Fixture
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }


    public function load(ObjectManager $manager): void
    {
     
        $faker = Faker\Factory::create('fr_FR');
        
       // les 3 categories
         for($cat = 1; $cat <= 10; $cat++){
            $categorie = new Categorie();
            $categorie->setTitle($faker->randomElement($array = array ('Char à voile','Catamaran','Char à voile kids')))
            //$category->setStock($faker->numberBetween($min = 5, $max = 8));
                      ->setDescription($faker->sentence())
                      ->setSlug(strtolower($this->slugger->slug($categorie->getTitle())));
            
           $manager->persist($categorie);
        }




        for($sean = 1; $sean <= 5; $sean++){
            $seance = new Seance();
            //$seance->setName($faker->randomElement($array = array ('Char à voile','Catamaran','Char à voile kids')));
            //$seance->setStock($faker->numberBetween($min = 5, $max = 8));
            $seance->setName("Seance n°$sean" . $this->slugger->slug($categorie->getTitle()))
                   ->setPrice('5000')
                   ->setQuantity('12')
                   ->setDatedelaseance($faker->dateTimeInInterval('0 week', '+10 days'))
                //  ->setSlug($this->slugger->slug($categorie->getTitle()));
                   ->setSlug(strtolower($this->slugger->slug($seance->getName())))
                   ->setCategorie($categorie->setTitle($faker->randomElement($array = array ('Char à voile','Catamaran','Char à voile kids'))))
                   ->setShortDescription($faker->paragraph());

            $manager->persist($seance);
        }

           for($p = 0; $p < mt_rand(5, 10); $p++){
            $purchase = new Purchase;
            
            $purchase->setFullName($faker->name)
               ->setAddress($faker->streetAddress)
               ->setPostalCode($faker->postcode)
               ->setCity($faker->city)
               ->setTotal(mt_rand(2000, 30000))
               ->getCreatedAt(($faker->dateTimeInInterval('0 week', '+10 days')));

            //90% de chance que le staut soit PAID
            if($faker->boolean(90)) {
                $purchase->setStatus(Purchase::STATUS_PAID);
            }

               $manager->persist($purchase);

         }



           
        $manager->flush();
    }
}
