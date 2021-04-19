<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\JWT\JWTService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private JWTEncoderInterface $jwtEncoder;
    private JWTService $jwtService;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        JWTEncoderInterface $jwtEncoder,
        JWTService $jwtService,
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtEncoder = $jwtEncoder;
        $this->jwtService = $jwtService;
    }

    #[Route('/api/register', name: 'register_user', methods: ['POST'])]
    public function registerUser(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        Request $request
    ): JsonResponse {
        $content = $request->toArray();

        $login = $content['login'] ?? '';
        $password = $content['password'] ?? '';
        $email = $content['email'] ?? '';

        if (!$login || !$password || !$email) {
            return new JsonResponse(
                ['message' => "One or more field is missing !"],
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($userRepository->usernameExists($login)) {
            return new JsonResponse(
                ['message' => "Username {$login} is already taken !"],
                Response::HTTP_CONFLICT
            );
        }

        $user = new User();
        $user->setLogin($login);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $user->setEmail($email);
        $user->setFirstname($content['firstname'] ?? '');
        $user->setLastname($content['lastname'] ?? '');

        $cart = new Cart();
        $cart->setUser($user);
        $user->setCart($cart);

        $entityManager->persist($user);
        $entityManager->persist($cart);
        $entityManager->flush();

        return new JsonResponse(
            ['message' => "Successfully saved new user ! (id: {$user->getId()})"],
            Response::HTTP_CREATED
        );
    }

    #[Route('/api/login', name: 'login_user', methods: ['POST'])]
    public function login()
    {
    }

    #[Route('/api/logout', name: 'logout_user', methods: ['POST'])]
    public function logout(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $login = JWTService::getLoginFromToken($request, $this->jwtEncoder);
        JWTService::cleanRefreshTokens($entityManager, $login);

        return new JsonResponse(['message' => "Successfully logged out !"]);
    }

    #[Route('/api/user', name: 'show_user', methods: ['GET'])]
    public function showUser(
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

        $response = $serializer->serialize($user, 'json', ['groups' => 'userinfo']);

        return JsonResponse::fromJsonString($response);
    }

    #[Route('/api/user', name: 'update_user', methods: ['PUT'])]
    public function updateUser(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        Request $request
    ): Response {
        $login = JWTService::getLoginFromToken($request, $this->jwtEncoder);
        $user = $userRepository->getUserIfExists($login);

        if (!$user) {
            return new JsonResponse(
                ['message' => "No user found for username {$login} !"],
                Response::HTTP_NOT_FOUND
            );
        }

        $content = $request->toArray();

        $oldLogin = $user->getLogin();
        $newLogin = $content['login'] ?? $oldLogin;
        $user->setLogin($newLogin);

        $newPassword = $content['password'] ?? '';
        if ($newPassword) $user->setPassword($this->passwordEncoder->encodePassword($user, $newPassword));

        $user->setEmail($content['email'] ?? $user->getEmail());
        $user->setFirstname($content['firstname'] ?? $user->getFirstname());
        $user->setLastname($content['lastname'] ?? $user->getLastname());

        $entityManager->flush();

        if ($oldLogin != $newLogin) { # Regenerate a whole new token in that case
            $jwt = $this->jwtService->createNewJWT($user);
            if ($jwt) { # Clean up old refresh token
                JWTService::cleanRefreshTokens($entityManager, $oldLogin);
            }
            return new JsonResponse($jwt);
        }

        return $this->redirectToRoute('show_user');
    }
}
