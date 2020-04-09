<?php

namespace App\DataFixtures;

use App\Entity\Catalog;
use App\Entity\Comment;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++) {
            $catalog = new Catalog();
            $catalog->setName('Catalog' . $i);

            $manager->persist($catalog);

            $manager = $this->createProduct($manager, $catalog);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     * @param Catalog $catalog
     * @return ObjectManager
     */
    private function createProduct(ObjectManager $manager, Catalog $catalog): ObjectManager
    {
        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName('Product' . $i);
            $product->setCatalog($catalog);

            $manager->persist($product);

            $manager = $this->createComments($manager, $product);
        }

        return $manager;
    }

    /**
     * @param ObjectManager $manager
     * @param Product $product
     * @return ObjectManager
     */
    private function createComments(ObjectManager $manager, Product $product): ObjectManager
    {
        for ($i = 0; $i < 20; $i++) {
            $comment = new Comment();
            $comment->setText('Comment ' . $i);
            $comment->setProduct($product);

            $manager->persist($comment);
        }

        return $manager;
    }
}