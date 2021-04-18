<?php

declare(strict_types=1);

namespace App\Service\JWT;

use App\Entity\User;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class JWTService
{
    /** @var JWTTokenManagerInterface */
    private $tokenManager;

    /** @var RefreshTokenManagerInterface */
    private $refreshTokenManager;

    /** @var ValidatorInterface */
    private $validator;

    /** @var int */
    private $ttl;

    public function __construct(
        JWTTokenManagerInterface $tokenManager,
        RefreshTokenManagerInterface $refreshTokenManager,
        ValidatorInterface $validator,
        int $ttl
    ) {
        $this->tokenManager = $tokenManager;
        $this->refreshTokenManager = $refreshTokenManager;
        $this->validator = $validator;
        $this->ttl = $ttl;
    }

    public function createNewJWT(User $user)
    {
        $token = $this->tokenManager->create($user);

        $datetime = new \DateTime();
        $datetime->modify('+'.$this->ttl.' seconds');

        $refreshToken = $this->refreshTokenManager->create();

        $refreshToken->setUsername($user->getUsername());
        $refreshToken->setRefreshToken();
        $refreshToken->setValid($datetime);

		// Validate, that the new token is a unique refresh token
        $valid = false;
        while (false === $valid) {
            $valid = true;
            $errors = $this->validator->validate($refreshToken);
            if ($errors->count() > 0) {
                foreach ($errors as $error) {
                    if ('refreshToken' === $error->getPropertyPath()) {
                        $valid = false;
                        $refreshToken->setRefreshToken();
                    }
                }
            }
        }

        $this->refreshTokenManager->save($refreshToken);

        $jwt = ['token' => $token, 'refresh_token' => $refreshToken->getRefreshToken()];
        return $jwt;
    }
}