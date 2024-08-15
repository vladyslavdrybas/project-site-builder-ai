<?php

declare(strict_types=1);

namespace App\Controller;

use App\Builder\UserBuilder;
use App\Repository\UserRepository;
use App\DataTransferObject\UserRegistrationDto;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;

#[Route('/auth', name: "auth")]
class AuthController extends SymfonyAbstractController
{
    #[Route('/register', name: '_register', methods: ["POST"])]
    public function index(
        #[MapRequestPayload] UserRegistrationDto $userRegisterDto,
        UserBuilder $userBuilder,
        UserRepository $repo
    ): RedirectResponse {

        $user = $userBuilder->base(
            $userRegisterDto->email,
            $userRegisterDto->password
        );

        $repo->add($user);
        $repo->save();

        return $this->redirect('app_homepage');
    }

    #[Route('/logout', name: '_logout', defaults: ['deviceType' => 'web'], methods: ['GET', 'POST', 'OPTIONS'])]
    public function logout(): never
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}
