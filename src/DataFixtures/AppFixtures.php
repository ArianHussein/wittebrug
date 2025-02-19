<?php

namespace App\DataFixtures;

use App\Entity\Department;
use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // first: composer require --dev orm-fixtures
        // terugzetten: php bin/console doctrine:fixtures:load
        // $product = new Product();
        // $manager->persist($product);
        $department1 = new Department();
        $department1->setName("sales");
        $manager->persist($department1);

        $department2 = new Department();
        $department2->setName("accounting");
        $manager->persist($department2);

        $department3 = new Department();
        $department3->setName("reparation");
        $manager->persist($department3);

        $employee1 = new Employee();
        $employee1->setFirstName("Donald");
        $employee1->setLastName("Duck");
        $employee1->setSalary(3200);
        $employee1->setDepartment($department1);
        $manager->persist($employee1);

        $employee2 = new Employee();
        $employee2->setFirstName("Donald");
        $employee2->setLastName("Trump");
        $employee2->setSalary(1550);
        $employee2->setDepartment($department3);
        $manager->persist($employee2);

        $employee3 = new Employee();
        $employee3->setFirstName("Katrien");
        $employee3->setLastName("Duck");
        $employee3->setSalary(2250);
        $employee3->setDepartment($department1);
        $manager->persist($employee3);

        $manager->flush();
    }
}
