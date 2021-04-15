<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;

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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    #[Route('/api/register', name: 'register_user', methods: ['POST'])]
    public function registerUser(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        Request $request
    ): Response {
        $user = new User();

        $content = $request->toArray();
        $user->setLogin($content['login']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $content['password']));
        $user->setEmail($content['email']);
        $user->setFirstname($content['firstname'] ?? '');
        $user->setLastname($content['lastname'] ?? '');

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('Saved new user with id ' . $user->getId());
    }
}
