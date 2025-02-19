<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Pizza;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categorie1 = new Categorie();
        $categorie1->setName("sales");
        $categorie1->setImg("img/sales.jpg"); // Voeg een afbeelding toe
        $manager->persist($categorie1);

        $categorie2 = new Categorie();
        $categorie2->setName("accounting");
        $categorie2->setImg("img/accounting.jpg"); // Voeg een afbeelding toe
        $manager->persist($categorie2);

        // Voeg meer categorieën en hun afbeeldingen toe...

        $manager->flush();
    }
}