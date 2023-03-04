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
        $role->setName('ROLE_ADMIN');
        $role->setLevel(100)->setDescription('Top level admin role, full privileges');
        $manager->persist($role);
        $role = new Roles(); 
        $role->setName('ADMIN_SITE');
        $role->setLevel(100)->setDescription('Top level admin role, full privileges');
        $manager->persist($role);
        $role = new Roles(); 
        $role->setName('ADMIN_USERS');
        $role->setLevel(80)->setDescription('Full privileges on user administration');
        $manager->persist($role);
        $role = new Roles(); 
        $role->setName('LOGGED');
        $role->setLevel(50)->setDescription('Logged user can access public and optional features');
        $manager->persist($role);
        $role = new Roles(); 
        $role->setName('ANONYMOUS');
        $role->setLevel(10)->setDescription('Any user can access public features ');
        $manager->persist($role);

        $manager->flush();
    }
}
