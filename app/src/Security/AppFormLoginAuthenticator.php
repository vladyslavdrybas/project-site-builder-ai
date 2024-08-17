<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppFormLoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'security_login';

    public function __construct(
        protected readonly UrlGeneratorInterface $urlGenerator,
        protected readonly UserProviderInterface $userProvider,
    ) {
    }

    public function authenticate(Request $request): Passport
    {
        $credentials = $this->getCredentials($request);

        $userBadge = new UserBadge(
            $credentials['userIdentifier'],
            $this->userProvider->loadUserByIdentifier(...)
        );

        $csrfTokenBadge = new CsrfTokenBadge(
            'authenticate',
            $request->getPayload()->getString('_csrf_token')
        );
        $rememberMeBadge = new RememberMeBadge();
        $passwordCredentials = new PasswordCredentials($credentials['password']);

        $passport = new Passport(
            $userBadge,
            $passwordCredentials,
            [
                $csrfTokenBadge,
                $rememberMeBadge,
            ]);

        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
         return new RedirectResponse($this->urlGenerator->generate('app_homepage'));
//        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    protected function getCredentials(Request $request): array
    {
        $credentials = [];
        $credentials['csrf_token'] = $request->getPayload()->getString('_csrf_token');
        $credentials['password'] = trim($request->getPayload()->getString('password'));
        $credentials['userIdentifier'] = trim($request->getPayload()->getString('email'));

        return $credentials;
    }
}
