<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\JWT\JWTService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CartController extends AbstractController
{

    private JWTEncoderInterface $jwtEncoder;

    public function __construct(JWTEncoderInterface $jwtEncoder)
    {
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

        $result = ['cart' => $cart, 'user' => $user];

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
    public function showCart(
        SerializerInterface $serializer,
        UserRepository $userRepository,
        Request $request
    ): JsonResponse {
        $content = $this->checkRequest($request, $userRepository);

        if ($content instanceof JsonResponse) {
            return $content;
        }

        $cart = $content['cart'];

        $products = $cart->getProducts();
        $response = $serializer->serialize($products, 'json');

        return JsonResponse::fromJsonString($response);
    }

    #[Route('/api/cart/validate', name: 'validate_cart', methods: ['POST'])]
    public function validateCart(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        CartRepository $cartRepository,
        Request $request
    ): JsonResponse {
        $content = $this->checkRequest($request, $userRepository);

        if ($content instanceof JsonResponse) {
            return $content;
        }

        ['cart' => $cart, 'user' => $user] = $content;
        $products = $cart->getProducts();

        if (!sizeof($products)) {
            return new JsonResponse(
                ['message' => "Cart is empty !"],
                Response::HTTP_BAD_REQUEST
            );
        }

        $totalPrice = $cartRepository->calculateTotalPrice($cart);
        $creationDate = new DateTime('now');

        $order = new Order();
        $order->setTotalPrice($totalPrice);
        $order->setCreationDate($creationDate);

        foreach ($products as $product) {
            $order->addProduct($product);
            $cart->removeProduct($product);
        }

        $order->setUser($user);
        $user->addOrder($order);

        $entityManager->persist($order);
        $entityManager->flush();

        return new JsonResponse(
            ['message' => "Successfully validated order ! (id: {$order->getId()})"],
            Response::HTTP_CREATED
        );
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

        ['cart' => $cart, 'product' => $product, 'user' => $user] = $content;

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
