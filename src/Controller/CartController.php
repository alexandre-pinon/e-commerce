<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\JWT\JWTService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CartController extends AbstractController
{

    private Serializer $serializer;
    private JWTEncoderInterface $jwtEncoder;

    public function __construct(JWTEncoderInterface $jwtEncoder)
    {
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $this->jwtEncoder = $jwtEncoder;
    }

    public function checkRequest(
        Request $request,
        UserRepository $userRepository,
        ProductRepository $productRepository = null,
        int $productId = 0
    ): mixed {
        $login = JWTService::getLoginFromToken($request, $this->jwtEncoder);
        $user = $userRepository->getUserIfExists($login);

        if (!$user) {
            return new JsonResponse(
                ['message' => "No user found for username {$login} !"],
                Response::HTTP_NOT_FOUND
            );
        }

        $cart = $user->getCart();

        if (!$cart) {
            return new JsonResponse(
                ['message' => "No cart found for user {$login} !"],
                Response::HTTP_NOT_FOUND
            );
        }

        $result = ['cart' => $cart];

        if ($productId && $productRepository) {
            $product = $productRepository->find($productId);

            if (!$product) {
                return new JsonResponse(
                    ['message' => "No product found for productId {$productId} !"],
                    Response::HTTP_NOT_FOUND
                );
            }

            $result['product'] = $product;
        }

        return $result;
    }

    #[Route('/api/cart', name: 'show_cart', methods: ['GET'])]
    public function showCart(UserRepository $userRepository, Request $request): JsonResponse
    {
        $content = $this->checkRequest($request, $userRepository);

        if ($content instanceof JsonResponse) {
            return $content;
        }

        $cart = $content['cart'];

        $products = $cart->getProducts();
        $response = $this->serializer->serialize($products, 'json');

        return JsonResponse::fromJsonString($response);
    }

    #[Route('/api/cart/{productId}', name: 'add_product', methods: ['POST'])]
    public function addProduct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        UserRepository $userRepository,
        int $productId,
        Request $request
    ): JsonResponse {
        $content = $this->checkRequest($request, $userRepository, $productRepository, $productId);

        if ($content instanceof JsonResponse) {
            return $content;
        }

        $cart = $content['cart'];

        $cart->addProduct($content['product']);
        $entityManager->flush();

        return new JsonResponse(['message' => "Successfully added product to cart ! (id: {$cart->getId()})"]);
    }

    #[Route('/api/cart/{productId}', name: 'remove_product', methods: ['DELETE'])]
    public function removeProduct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        UserRepository $userRepository,
        int $productId,
        Request $request
    ): JsonResponse {
        $content = $this->checkRequest($request, $userRepository, $productRepository, $productId);

        if ($content instanceof JsonResponse) {
            return $content;
        }

        ['cart' => $cart, 'product' => $product] = $content;

        if (!$cart->getProducts()->contains($product)) {
            return new JsonResponse(
                ['message' => "Product {$productId} not found in cart {$cart->getId()} !"],
                Response::HTTP_NOT_FOUND
            );
        }

        $cart->removeProduct($product);
        $entityManager->flush();

        return new JsonResponse(['message' => "Successfully removed product from cart ! (id: {$cart->getId()})"]);
    }
}
