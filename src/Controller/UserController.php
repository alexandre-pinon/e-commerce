<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;

use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\HttpKernelInterface;
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

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        JWTEncoderInterface $jwtEncoder,
    ) {
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $this->passwordEncoder = $passwordEncoder;
        $this->jwtEncoder = $jwtEncoder;
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

    #[Route('/api/login', name: 'login_user', methods: ['GET', 'POST'])]
    public function login() {}

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

        if ($oldLogin != $newLogin) {
            # TODO: Token needs to be updated in that case
            $message = "Successfully updated user ! (id: {$user->getId()}) Please relog to refresh your token !";
            return new JsonResponse(['message' => $message]);
        }

        return $this->redirectToRoute('show_user');
    }

    public function forward(string $controller, array $path = [], array $query = [], array $body = []): Response
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $path['_controller'] = $controller;
        $subRequest = $request->duplicate($query, $body, $path);

        return $this->container->get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
    }
}
