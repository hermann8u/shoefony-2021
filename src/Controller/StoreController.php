<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Store\Comment;
use App\Form\CommentType;
use App\Repository\Store\BrandRepository;
use App\Repository\Store\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class StoreController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ProductRepository $productRepository,
        private BrandRepository $brandRepository,
    ) {
    }

    #[Route('/store/products', name: 'store_list_products')]
    public function listProducts(): Response
    {
        $products = $this->productRepository->findAllWithDetails();

        return $this->render('store/list_products.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/store/brand/{brandId}/products', name: 'store_list_products_by_brands', requirements: ['brandId' => '\d+'], methods: ['GET'])]
    public function listProductsByBrand(int $brandId): Response
    {
        $brand = $this->brandRepository->find($brandId);
        if ($brand === null) {
            throw new NotFoundHttpException();
        }

        $products = $this->productRepository->findProductsByBrand($brand);

        return $this->render('store/list_products.html.twig', [
            'products' => $products,
            'brand' => $brand,
        ]);
    }
    
    #[Route('/store/products/{id}/details/{slug}', name: 'store_show_product', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function showProduct(Request $request, int $id, string $slug): Response
    {
        $product = $this->productRepository->findOneWithDetails($id);
        if ($product === null) {
            throw new NotFoundHttpException();
        }

        if ($slug !== $product->getSlug()) {
            return $this->redirectToRoute('store_show_product', [
                'id' => $id,
                'slug' => $product->getSlug(),
            ], Response::HTTP_MOVED_PERMANENTLY);
        }

        $comment = new Comment();
        $comment->setProduct($product);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre commentaire a été enregistré !');

            $this->em->persist($comment);
            $this->em->flush();

            return $this->redirectToRoute('store_show_product', ['id' => $id, 'slug' => $slug]);
        }

        return $this->render('store/show_product.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    public function listBrands(?int $brandId): Response
    {
        return $this->render('store/_list_brands.html.twig', [
            'brands' => $this->brandRepository->findAll(),
            'brandId' => $brandId,
        ]);
    }
}
