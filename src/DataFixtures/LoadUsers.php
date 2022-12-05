<?php

namespace App\DataFixtures;

use App\Entity\Users;
use DateTime;
use DateTimeZone;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoadUsers extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    )
    {
        
    }
    
    public function load(ObjectManager $manager): void
    {
        $admin1 = new Users();
        $admin1->setFirstname('Benjamin');
        $admin1->setLastname('Toubhans');
        $admin1->setAddress('La Lune 3ème étage');
        $admin1->setEmail('yves77340@gmail.com');
        $admin1->setPassword($this->hasher->hashPassword($admin1, 'admin'));
        $admin1->setConfirmpassword($this->hasher->hashPassword($admin1, 'admin'));
        $admin1->setRole(['ROLE_ADMIN']);
        $admin1->setCreated(new DateTime('now', new DateTimeZone('Europe/Paris')));
        
        $admin2 = new Users();
        $admin2->setFirstname('Yves');
        $admin2->setLastname('Toubhans');
        $admin2->setAddress('Mars with Elon');
        $admin2->setEmail('benjamin.toubhans@gmail.com');
        $admin2->setPassword($this->hasher->hashPassword($admin2, 'admin'));
        $admin2->setConfirmpassword($this->hasher->hashPassword($admin2, 'admin'));
        $admin2->setRole(['ROLE_ADMIN']);
        $admin2->setCreated(new DateTime('now', new DateTimeZone('Europe/Paris')));

        $admin3 = new Users();
        $admin3->setFirstname('Isabelle');
        $admin3->setLastname('Toubhans');
        $admin3->setAddress('Pluton with Jeff');
        $admin3->setEmail('i.toubhans@free.fr');
        $admin3->setPassword($this->hasher->hashPassword($admin3, 'admin'));
        $admin3->setConfirmpassword($this->hasher->hashPassword($admin3, 'admin'));
        $admin3->setRole(['ROLE_SUPER_ADMIN']);
        $admin3->setCreated(new DateTime('now', new DateTimeZone('Europe/Paris')));
        
        $manager->persist($admin1);
        $manager->persist($admin2);
        $manager->persist($admin3);

        for($i=1; $i<5; $i++){
            $user = new Users();
            $user->setFirstname("userfname$i");
            $user->setLastname("userlname$i");
            $user->setAddress("$i rue de la paix et de la chasse");
            $user->setEmail("user$i@gmail.com");
            $user->setPassword($this->hasher->hashPassword($user, '123456'));
            $user->setConfirmpassword($this->hasher->hashPassword($user, '123456'));
            $user->setCreated(new DateTime('now', new DateTimeZone('Europe/Paris')));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
