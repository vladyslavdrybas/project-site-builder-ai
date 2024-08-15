<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    protected FlashBagInterface $flash;

    public function __construct(
        protected readonly RequestStack $requestStack
    ) {
        $this->flash = $this->requestStack->getSession()->getFlashBag();
    }

    public function checkPreAuth(UserInterface $user): void
    {
//        $this->flash->set('danger', __METHOD__);
        if (!$user instanceof User) {
            return;
        }

        if ($user->isDeleted()) {
            throw new LockedException();
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
//        $this->flash->set('danger', __METHOD__);
    }
}
