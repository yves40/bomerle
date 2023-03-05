<?php

namespace App\DataFixtures;

use DateTime;
use DateTimeZone;

use App\Entity\Roles;
use App\Entity\Users;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
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
        $repo = $manager->getRepository(Roles::class);

        $admin1 = new Users();
        $admin1->setFirstname('Yves');
        $admin1->setLastname('Toubhans');
        $admin1->setAddress('La Lune 3ème étage');
        $admin1->setEmail('yves77340@gmail.com');
        $admin1->setPassword($this->hasher->hashPassword($admin1, 'manager1'));
        $admin1->setConfirmpassword($this->hasher->hashPassword($admin1, 'manager1'));
        $admin1->setCreated(new DateTime('now', new DateTimeZone('Europe/Paris')));
        $admin1->setConfirmed(new DateTime('now', new DateTimeZone('Europe/Paris')));
        $role = $repo->findOneBy([ 'name' => 'ROLE_ADMINISTRATOR'] );
        $admin1->addRole($role);
        $role = $repo->findOneBy([ 'name' => 'ROLE_ANONYMOUS'] );
        $admin1->addRole($role);

        $manager->persist($admin1);
        
        $admin2 = new Users();
        $admin2->setFirstname('Benjamin');
        $admin2->setLastname('Toubhans');
        $admin2->setAddress('Mars with Elon');
        $admin2->setEmail('benjamin.toubhans@orange.fr');
        $admin2->setPassword($this->hasher->hashPassword($admin2, 'manager1'));
        $admin2->setConfirmpassword($this->hasher->hashPassword($admin2, 'manager1'));
        $admin2->setCreated(new DateTime('now', new DateTimeZone('Europe/Paris')));
        $admin2->setConfirmed(new DateTime('now', new DateTimeZone('Europe/Paris')));
        $role = $repo->findOneBy([ 'name' => 'ROLE_ADMIN_USERS'] );
        $admin2->addRole($role);
        $role = $repo->findOneBy([ 'name' => 'ROLE_ANONYMOUS'] );
        $admin2->addRole($role);

        $manager->persist($admin2);

        $admin3 = new Users();
        $admin3->setFirstname('Isabelle');
        $admin3->setLastname('Toubhans');
        $admin3->setAddress('Pluton with Jeff');
        $admin3->setEmail('i.toubhans@free.fr');
        $admin3->setPassword($this->hasher->hashPassword($admin3, 'manager1'));
        $admin3->setConfirmpassword($this->hasher->hashPassword($admin3, 'manager1'));
        $admin3->setCreated(new DateTime('now', new DateTimeZone('Europe/Paris')));
        $admin3->setConfirmed(new DateTime('now', new DateTimeZone('Europe/Paris')));
        $role = $repo->findOneBy([ 'name' => 'ROLE_ADMIN_SITE'] );
        $admin3->addRole($role);
        $role = $repo->findOneBy([ 'name' => 'ROLE_ANONYMOUS'] );
        $admin3->addRole($role);

        $manager->persist($admin3);

        $fakeusers = [ 
            [ 'fname' => 'Albert', 'lname' => 'Warwick', 'address' => 'Dernier domicile connu' ],
            [ 'fname' => 'Maurice', 'lname' => 'Mansel', 'address' => 'Dernier domicile connu' ],
            [ 'fname' => 'Claude', 'lname' => 'Andretti', 'address' => 'Dernier domicile connu' ],
            [ 'fname' => 'Claudine', 'lname' => 'Villeneuve', 'address' => 'Dernier domicile connu' ],
            [ 'fname' => 'Clarence', 'lname' => 'Barensac', 'address' => 'Dernier domicile connu' ],
        ];
        $role = $repo->findOneBy([ 'name' => 'ROLE_ANONYMOUS'] );
        foreach( $fakeusers as $oneuser) {
            $user = new Users();
            $user->setFirstname($oneuser['fname']);
            $user->setLastname($oneuser['lname']);
            $user->setAddress($oneuser['address']);
            $user->setEmail(strtolower($oneuser['fname'].'.'.$oneuser['lname'].'@gmail.com'));
            $user->setPassword($this->hasher->hashPassword($user, '1234'));
            $user->setConfirmpassword($this->hasher->hashPassword($user, '1234'));
            $user->setCreated(new DateTime('now', new DateTimeZone('Europe/Paris')));
            $user->setConfirmed(new DateTime('now', new DateTimeZone('Europe/Paris')));
            $user->addRole($role);
            $manager->persist($user);
        }

        $manager->flush();
    }
    public function getDependencies() {
        return [
            LoadRoles::class
        ];
    }
}
