<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class StoreController extends AbstractController
{
    #[Route('/store/product', name: 'store_list_products')]
    public function listProducts(): Response
    {
        return $this->render('store/list_products.html.twig');
    }
    
    #[Route('/store/product/{id}/details/{slug}', name: 'store_show_product', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showProduct(int $id, string $slug): Response
    {
        return $this->render('store/show_product.html.twig');
    }
}
