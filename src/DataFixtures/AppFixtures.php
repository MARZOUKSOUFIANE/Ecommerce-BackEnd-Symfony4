<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;


class AppFixtures extends Fixture
{
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository=$categoryRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');


        // on créé des produits pour chaque categorie
        foreach ($this->categoryRepository->findAll() as $category) {
            for($i=0;$i<4;$i++){
            $product = new Product();
            $product->setName($faker->words(3,true));
            $product->setDescription($faker->sentences(1,true));
            $product->setCurrentPrice($faker->numberBetween(50,400));
            $product->setAvailable((bool)random_int(0,1));
            $product->setSelected((bool)random_int(0,1));
            $product->setPhotoName("unknown.png");
            $product->setPromotion((bool)random_int(0,1));
            $product->setCategory($category);

            $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
