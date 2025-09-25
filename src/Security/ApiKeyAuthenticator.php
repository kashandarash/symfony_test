<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ApiKeyAuthenticator extends AbstractAuthenticator {

  const API_KEY = 'apiKey';
  const API_USER = 'apiUser';

  public function __construct(
    private JWTTokenManagerInterface $jwtManager,
    private ParameterBagInterface  $params,
    private string $apiKey
  ) {}

  public function supports(Request $request): ?bool {
    // Try to authenticate only our route calls.
    return $request->attributes->get('_route') === 'api_key_login';
  }

  public function authenticate(Request $request): Passport {
    $data = json_decode($request->getContent(), TRUE);

    if (empty($data[self::API_KEY]) || empty($data[self::API_USER])) {
      throw new AuthenticationException('API parameters are missing');
    }

    $apiKey = $data[self::API_KEY];
    // Comparing the request API key and key from config.
    if ($apiKey !== $this->apiKey) {
      throw new AuthenticationException('Invalid API Key');
    }

    return new SelfValidatingPassport(
      new UserBadge($data[self::API_USER])
    );
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response {
    $user = $token->getUser();

    return new JsonResponse([
      'token' => $this->jwtManager->create($user),
      'token_ttl' => (int) $this->params->get('lexik_jwt_authentication.token_ttl'),
    ]);
  }

  public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response {
    // Set message from exception.
    return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_UNAUTHORIZED);
  }

}