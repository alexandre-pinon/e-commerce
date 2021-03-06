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
use Symfony\Component\Serializer\SerializerInterface;

class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'show_all_products', methods: ['GET'])]
    public function showAllProducts(SerializerInterface $serializer, ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();
        $response = $serializer->serialize($products, 'json');

        return JsonResponse::fromJsonString($response);
    }

    #[Route('/api/product/{productId}', name: 'show_product', methods: ['GET'])]
    public function showProduct(
        SerializerInterface $serializer,
        ProductRepository $productRepository,
        int $productId
    ): JsonResponse {
        $product = $productRepository->find($productId);

        if (!$product) {
            return new JsonResponse(
                ['message' => "No product found for productId {$productId} !"],
                Response::HTTP_NOT_FOUND
            );
        }

        $response = $serializer->serialize($product, 'json');

        return JsonResponse::fromJsonString($response);
    }

    #[Route('/api/product', name: 'create_product', methods: ['POST'])]
    public function createProduct(
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse {
        $product = new Product();

        $content = $request->toArray();
        $name = $content['name'] ?? '';
        $photo = $content['photo'] ?? '';
        $price = $content['price'] ?? '';

        if (!$name || !$photo || !$price) {
            return new JsonResponse(
                ['message' => "One or more field is missing !"],
                Response::HTTP_BAD_REQUEST
            );
        }

        $product->setName($name);
        $product->setDescription($content['description'] ?? '');
        $product->setPhoto($photo);
        $product->setPrice($price);

        $entityManager->persist($product);
        $entityManager->flush();

        return new JsonResponse(
            ['message' => "Successfully saved new product ! (id: {$product->getId()})"],
            Response::HTTP_CREATED
        );
    }

    #[Route('/api/product/{productId}', name: 'update_product', methods: ['PUT'])]
    public function updateProduct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        Request $request,
        int $productId,
    ): Response {
        $product = $productRepository->find($productId);

        if (!$product) {
            return new JsonResponse(
                ['message' => "No product found for productId {$productId} !"],
                Response::HTTP_NOT_FOUND
            );
        }

        $content = $request->toArray();
        $product->setName($content['name'] ?? $product->getName());
        $product->setDescription($content['description'] ?? $product->getDescription());
        $product->setPhoto($content['photo'] ?? $product->getPhoto());
        $product->setPrice($content['price'] ?? $product->getPrice());
        $entityManager->flush();

        // return $this->redirectToRoute('show_product', [
        //     'productId' => $productId
        // ]);
        return new JsonResponse(
            ['message' => "Successfully updated product {$product->getId()} !"],
        );
    }

    #[Route('/api/product/{productId}', name: 'delete_product', methods: ['DELETE'])]
    public function deleteProduct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        int $productId,
    ): JsonResponse {
        $product = $productRepository->find($productId);

        if (!$product) {
            return new JsonResponse(
                ['message' => "No product found for productId {$productId} !"],
                Response::HTTP_NOT_FOUND
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return new JsonResponse(['message' => "Successfully deleted product ! (id: {$productId})"]);
    }
}
