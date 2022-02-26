<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Store\Brand;
use App\Entity\Store\Color;
use App\Entity\Store\Comment;
use App\Entity\Store\Image;
use App\Entity\Store\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    private const DATA_BRANDS = [
        ['Adidas'],
        ['Asics'],
        ['Nike'],
        ['Puma'],
    ];

    private const DATA_COLORS = [
        ['Jaune'],
        ['Rouge'],
        ['Bleu'],
        ['Noir'],
        ['Blanc'],
    ];

    private const DATA_COMMENTS_USERNAMES = [
        'CrazyRiku',
        'WhiteStone',
        'OmegaStorm',
        'FouSmall',
    ];

    private const DATA_COMMENTS_MESSAGES = [
        'Super produit ! Je recommande',
        'Attention, ça taille grand !',
        'Mouais, bof',
        'Pas ouf :/',
        'Parfait pour mes séances de running intensives !'
    ];

    private ObjectManager $manager;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadBrands();
        $this->loadColors();
        $this->loadProducts();

        $manager->flush();
    }

    private function loadBrands(): void
    {
        foreach (self::DATA_BRANDS as $key => [$name]) {
            $brand = (new Brand())
                ->setName($name)
            ;

            $this->manager->persist($brand);
            $this->addReference(Brand::class . $key, $brand);
        }
    }

    private function loadColors(): void
    {
        foreach (self::DATA_COLORS as $key => [$name]) {
            $color = (new Color())
                ->setName($name)
            ;

            $this->manager->persist($color);
            $this->addReference(Color::class . $key, $color);
        }
    }

    private function loadProducts(): void
    {
        for ($i = 1; $i < 15; $i++) {
            $product = (new Product())
                ->setName('Product ' . $i)
                ->setDescription('Description du produit ' . $i)
                ->setLongDescription('Description longue du produit ' . $i)
                ->setPrice(mt_rand(10, 100))
                ->setBrand($this->getRandomEntityReference(Brand::class, count(self::DATA_BRANDS)))
            ;

            $product->setImage($this->createImage($i, $product->getName()));

            for ($j = 0; $j < random_int(0, count(self::DATA_COLORS) - 1); $j++) {
                if (random_int(0, 1)) {
                    /** @var Color $color */
                    $color = $this->getReference(Color::class . $j);
                    $product->addColor($color);
                }
            }

            for ($j = 0; $j < random_int(0, 20); $j++) {
                $comment = (new Comment())
                    ->setUsername(self::DATA_COMMENTS_USERNAMES[array_rand(self::DATA_COMMENTS_USERNAMES)])
                    ->setMessage(self::DATA_COMMENTS_MESSAGES[array_rand(self::DATA_COMMENTS_MESSAGES)])
                ;

                $product->addComment($comment);
            }

            $this->manager->persist($product);

            sleep(1);
        }
    }

    private function createImage(int $i, string $alt): Image
    {
        return (new Image())
            ->setUrl('shoe-' . $i . '.jpg')
            ->setAlt($alt)
        ;
    }

    /**
     * @param class-string $entityClass
     *
     * @return object<class-string>
     */
    private function getRandomEntityReference(string $entityClass, int $dataLength): object
    {
        return $this->getReference($entityClass . random_int(0, $dataLength - 1));
    }
}
