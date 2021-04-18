<?php

namespace App\Controller;

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
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private Serializer $serializer;
    private UserPasswordEncoderInterface $passwordEncoder;
    private JWTEncoderInterface $jwtEncoder;
    private JWTService $jwtService;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        JWTEncoderInterface $jwtEncoder,
        JWTService $jwtService,
    ) {
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtEncoder = $jwtEncoder;
        $this->jwtService = $jwtService;
    }

    #[Route('/api/register', name: 'register_user', methods: ['POST'])]
    public function registerUser(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        Request $request
    ): Response {
        $content = $request->toArray();
        if ($userRepository->usernameExists($content['login'])) {
            return new JsonResponse(['message' => "Username {$content['login']} is already taken !"], 409);
        }

        $user = new User();
        $user->setLogin($content['login']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $content['password']));
        $user->setEmail($content['email']);
        $user->setFirstname($content['firstname'] ?? '');
        $user->setLastname($content['lastname'] ?? '');

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse(['message' => "One or more fields contains errors !", 'errors' => $errors], 400);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['message' => "Successfully saved new user ! (id: {$user->getId()})"], 201);
    }

    #[Route('/api/login', name: 'login_user', methods: ['POST'])]
    public function login()
    {
    }

    #[Route('/api/user', name: 'show_user', methods: ['GET'])]
    public function showUser(UserRepository $userRepository, Request $request): Response
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = substr($authorizationHeader, 7); # skip beyond 'Bearer '
        $payload = $this->jwtEncoder->decode($token);
        $login = $payload['login'];

        $user = $userRepository->findOneBy(['login' => $login]);
        if (!$user) {
            throw $this->createNotFoundException(
                "No user found for username {$login} !"
            );
        }
        $response = $this->serializer->serialize($user, 'json');

        return JsonResponse::fromJsonString($response);
    }

    #[Route('/api/user', name: 'update_user', methods: ['PUT'])]
    public function updateUser(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        Request $request
    ): Response {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = substr($authorizationHeader, 7); # skip beyond 'Bearer '
        $payload = $this->jwtEncoder->decode($token);
        $login = $payload['login'];

        $user = $userRepository->findOneBy(['login' => $login]);
        if (!$user) {
            throw $this->createNotFoundException(
                "No user found for username {$login} !"
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
            return new JsonResponse($jwt);
        }

        return $this->redirectToRoute('show_user');
    }
}
