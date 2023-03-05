<?php

namespace App\DataFixtures;

use App\Entity\Roles;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadRoles extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $role = new Roles(); 
        $role->setName('ROLE_ADMINISTRATOR');
        $role->setLevel(100)->setDescription('Agregates all admin roles, look at security.yaml');
        $manager->persist($role);

        $role = new Roles(); 
        $role->setName('ROLE_ADMIN');
        $role->setLevel(90)->setDescription('Top level admin role, full privileges');
        $manager->persist($role);

        $role = new Roles(); 
        $role->setName('ROLE_ADMIN_SITE');
        $role->setLevel(90)->setDescription('Top level admin role, full privileges');
        $manager->persist($role);

        $role = new Roles(); 
        $role->setName('ROLE_ADMIN_USERS');
        $role->setLevel(80)->setDescription('Full privileges on user administration');
        $manager->persist($role);

        $role = new Roles(); 
        $role->setName('ROLE_ANONYMOUS');
        $role->setLevel(10)->setDescription('Any user can access public features ');
        $manager->persist($role);

        $manager->flush();
    }
}
