<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ProductController extends AbstractController
{

    private Serializer $serializer;

    public function __construct()
    {
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    #[Route('/api/products', name: 'show_all_products', methods: ['GET'])]
    public function showAllProducts(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();
        $response = $this->serializer->serialize($products, 'json');

        return JsonResponse::fromJsonString($response);
    }

    #[Route('/api/product/{productId}', name: 'show_product', methods: ['GET'])]
    public function showProduct(ProductRepository $productRepository, int $productId): JsonResponse
    {
        $product = $productRepository->find($productId);
        $response = $this->serializer->serialize($product, 'json');

        return JsonResponse::fromJsonString($response);
    }

    #[Route('/admin/product', name: 'create_product', methods: ['POST'])]
    public function createProduct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        Request $request
    ): Response {
        $product = new Product();

        $content = $request->toArray();
        $product->setName($content['name']);
        $product->setDescription($content['description'] ?? '');
        $product->setPhoto($content['photo']);
        $product->setPrice($content['price']);

        $errors = $validator->validate($product);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('Saved new product with id ' . $product->getId());
    }

    #[Route('/admin/product/{productId}', name: 'update_product', methods: ['PUT'])]
    public function updateProduct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        Request $request,
        int $productId,
    ): Response {
        $product = $productRepository->find($productId);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for productId ' . $productId
            );
        }

        $content = $request->toArray();
        $product->setName($content['name'] ?? $product->getName());
        $product->setDescription($content['description'] ?? $product->getDescription());
        $product->setPhoto($content['photo'] ?? $product->getPhoto());
        $product->setPrice($content['price'] ?? $product->getPrice());
        $entityManager->flush();

        return $this->redirectToRoute('show_product', [
            'productId' => $productId
        ]);
    }

    #[Route('/admin/product/{productId}', name: 'delete_product', methods: ['DELETE'])]
    public function deleteProduct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        int $productId,
    ): Response {
        $product = $productRepository->find($productId);

        $entityManager->remove($product);
        $entityManager->flush();

        return new Response('Deleted product with id ' . $productId);
    }
}
