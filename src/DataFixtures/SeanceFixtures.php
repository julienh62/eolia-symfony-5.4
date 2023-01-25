<?php

namespace App\DataFixtures;

use App\Entity\Purchase;
use App\Entity\PurchaseItem;
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
    private $passwordEncoder;
    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $passwordEncoder)
    {
        $this->slugger = $slugger;
        $this->passwordEncoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager): void
    {
     
        $faker = Faker\Factory::create('fr_FR');
        
       // les 3 categories
         for($cat = 1; $cat <= 6; $cat++){
            $categorie = new Categorie();
            $categorie->setTitle($faker->randomElement($array = array ('Char à voile','Catamaran','Char à voile kids')))
            //$category->setStock($faker->numberBetween($min = 5, $max = 8));
                      ->setSlug(strtolower($this->slugger->slug($categorie->getTitle())));
            
           $manager->persist($categorie);
        }

         $seances = [];

        for($sean = 1; $sean <= 5; $sean++){
            $seance = new Seance();
            //$seance->setName($faker->randomElement($array = array ('Char à voile','Catamaran','Char à voile kids')));
            //$seance->setStock($faker->numberBetween($min = 5, $max = 8));
            $seance->setName("Seance-n°$sean" . $this->slugger->slug($categorie->getTitle()))
                   ->setPrice('5000')
                   ->setQuantity('12')
                  ->setPicture('https://127.0.0.1:8000/assets/uploads/char-accueiltitregros1500.jpg')
                 ->setDatedelaseance($faker->dateTimeInInterval('0 week', '+10 days'))
                //  ->setSlug($this->slugger->slug($categorie->getTitle()));
                   ->setSlug(strtolower($this->slugger->slug($seance->getTitle())))
                   ->setCategorie($categorie->setTitle($faker->randomElement($array = array ('Char à voile','Catamaran','Char à voile kids'))))
                   ->setShortDescription($faker->paragraph());

            $seances[] = $seance;

            $manager->persist($seance);
        }


        $admin = new User();
        $admin->setEmail('jhennebo@gmail.com')
            ->setFullName('julien hennebo')
            ->setPassword(
                $this->passwordEncoder->hashPassword($admin, 'azerty')
            )
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $faker = Faker\Factory::create('fr_FR');

        $users = [];

        for ($usr = 1; $usr <= 5; $usr++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setFullName($faker->name())
                ->setPassword(
                    $this->passwordEncoder->hashPassword($user, 'secret')
                );
         $users[] = $user;

            $manager->persist($user);
        }




           for($p = 0; $p < mt_rand(5, 10); $p++){
            $purchase = new Purchase;
            
            $purchase->setFullName($faker->name)
               ->setAddress($faker->streetAddress)
               ->setPostalCode($faker->postcode)
               ->setCity($faker->city)
               ->setTotal(mt_rand(2000, 30000))
               ->setCreatedAt(($faker->dateTimeInInterval('0 week', '+10 days')))
               ->setUser($faker->randomElement($users));

            $selectedSeances = $faker->randomElements($seances, mt_rand(3, 5));

            foreach ($selectedSeances as $seance) {
                $purchaseItem = new PurchaseItem();
                $purchaseItem->setSeance($seance)
                    ->setQuantity(mt_rand(1, 3))
                ->setSeanceName($seance->getName())
                ->setSeancePrice($seance->getPrice())
                ->setTotal(
                    $purchaseItem->getSeancePrice() * $purchaseItem->getQuantity()
                )
                    ->setPurchase($purchase);

                $manager->persist($purchaseItem);
            }



            //90% de chance que le staut soit PAID
            if($faker->boolean(90)) {
                $purchase->setStatus(Purchase::STATUS_PAID);
            }

               $manager->persist($purchase);

         }



           
        $manager->flush();
    }
}
