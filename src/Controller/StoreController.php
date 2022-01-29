<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\Store\BrandRepository;
use App\Repository\Store\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class StoreController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private BrandRepository $brandRepository,
    ) {
    }

    #[Route('/store/product', name: 'store_list_products')]
    public function listProducts(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->render('store/list_products.html.twig', [
            'brands' => $this->brandRepository->findAll(),
            'products' => $products,
        ]);
    }
    
    #[Route('/store/product/{id}/details/{slug}', name: 'store_show_product', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function showProduct(int $id, string $slug): Response
    {
        $product = $this->productRepository->find($id);
        if ($product === null) {
            throw new NotFoundHttpException();
        }

        if ($slug !== $product->getSlug()) {
            return $this->redirectToRoute('store_show_product', [
                'id' => $id,
                'slug' => $product->getSlug(),
            ], Response::HTTP_MOVED_PERMANENTLY);
        }

        return $this->render('store/show_product.html.twig', [
            'brands' => $this->brandRepository->findAll(),
            'product' => $product,
        ]);
    }
}
