<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Store\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadProducts();

        $manager->flush();
    }

    private function loadProducts(): void
    {
        for ($i = 1; $i < 15; $i++) {
            $product = (new Product())
                ->setName('Product ' . $i)
                ->setDescription('Description du produit ' . $i)
                ->setPrice(mt_rand(10, 100));

            $this->manager->persist($product);
        }
    }
}
