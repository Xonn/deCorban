<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Galery;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GaleryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // Création de catégories
        for($i = 1; $i <= 8; $i++) {
            $category = new Category();
            $category->setName($faker->word());

            $manager->persist($category);

            $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '<p>';

            for($j = 1; $j <= mt_rand(3, 7); $j++) {
                $img = '';
                $img = 'https://source.unsplash.com/random/1300x2000';
                $galery = new Galery();
                $galery->setTitle($faker->sentence())
                       ->setDescription($content)
                       ->setThumbnail($img)
                       ->setCreatedAt($faker->dateTimeBetween('-11 months'))
                       ->addCategory($category);
    
                $manager->persist($galery);
            }
        } 
        $manager->flush();
    }
}
