<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Service\JWT\JWTService;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class OrderController extends AbstractController
{

    private JWTEncoderInterface $jwtEncoder;

    public function __construct(JWTEncoderInterface $jwtEncoder)
    {
        $this->jwtEncoder = $jwtEncoder;
    }

    #[Route('/api/orders', name: 'show_all_orders', methods: ['GET'])]
    public function showAllOrders(
        SerializerInterface $serializer,
        UserRepository $userRepository,
        Request $request
    ): JsonResponse {
        $login = JWTService::getLoginFromToken($request, $this->jwtEncoder);
        $user = $userRepository->getUserIfExists($login);

        if (!$user) {
            return new JsonResponse(
                ['message' => "No user found for username {$login} !"],
                Response::HTTP_NOT_FOUND
            );
        }

        $orders = $user->getOrders();
        $response = $serializer->serialize($orders, 'json', ['groups' => 'orderinfo']);

        return JsonResponse::fromJsonString($response);
    }

    #[Route('/api/order/{orderId}', name: 'show_order', methods: ['GET'])]
    public function showOrder(
        SerializerInterface $serializer,
        OrderRepository $orderRepository,
        int $orderId
    ): JsonResponse {
        $order = $orderRepository->find($orderId);

        if (!$order) {
            return new JsonResponse(
                ['message' => "No order found for orderId {$orderId} !"],
                Response::HTTP_NOT_FOUND
            );
        }

        $response = $serializer->serialize($order, 'json', ['groups' => 'orderinfo']);

        return JsonResponse::fromJsonString($response);
    }
}
