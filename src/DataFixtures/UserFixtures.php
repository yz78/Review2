<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private $userPassword;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPassword=$userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker=Factory::create("fr_FR");

        $adminmous=new User();
        $adminmous      ->setEmail("admin.moussa@review.com")
                        ->setRoles(['ROLE_ADMIN'])
                        ->setPassword( $this->userPassword->hashPassword(
                            $adminmous,
                            "adminmous1234"
                        ))
                        ->setNom("Dia")
                        ->setPrenom("Moussa")
                        ->setDateNaissance(new \DateTime('2014-12-04'))
                        ->setDateInscription(new \DateTime('2022-01-13'))
                        ->setPhoto("https://image.com")
                        ->setPseudo("adminMous");
                                       
            $manager->persist($adminmous);

        $adminfayez=new User();
        $adminfayez     ->setEmail("admin.fayeza@review.com")
                        ->setRoles(['ROLE_ADMIN'])
                        ->setPassword( $this->userPassword->hashPassword(
                             $adminfayez,
                            "adminfayez1234"
                        ))
                        ->setNom("Tib")
                        ->setPrenom("Fayeza")
                        ->setDateNaissance(new \DateTime('2014-12-04'))
                        ->setDateInscription(new \DateTime('2022-01-13'))
                        ->setPhoto("https://image.com")
                        ->setPseudo("adminFaye");
                                           
        $manager->persist($adminfayez);
            
        $adminyaz=new User();
        $adminyaz       ->setEmail("admin.yazid@review.com")
                        ->setRoles(['ROLE_ADMIN'])
                        ->setPassword( $this->userPassword->hashPassword(
                            $adminmous,
                            "adminyaz1234"
                        ))
                        ->setNom("Aou")
                        ->setPrenom("Yazid")
                        ->setDateNaissance(new \DateTime('2014-12-04'))
                        ->setDateInscription(new \DateTime('2022-01-13'))
                        ->setPhoto("https://image.com")
                        ->setPseudo("adminYaz");
                                               
        $manager->persist($adminyaz);

        $manager->flush();
    }
}
