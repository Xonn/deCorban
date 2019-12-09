<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Galery;
class GaleryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++) {
            $galery = new Galery();
            $galery->setTitle("Galerie n°" . $i)
                   ->setDescription("<p>Description de la galerie</p>")
                   ->setImage("http://placehold.it/350x150")
                   ->setCreatedAt(new \DateTime());

            $manager->persist($galery);
        }
        $manager->flush();
    }
}
