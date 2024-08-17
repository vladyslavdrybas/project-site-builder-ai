<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\UserInterface;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    public function __construct(
        protected readonly VerifyEmailHelperInterface $verifyEmailHelper,
        protected readonly MailerInterface $mailer,
        protected readonly UserRepository $userRepository
    ) {}

    public function sendEmailConfirmation(
        string $verifyEmailRouteName,
        UserInterface $user,
        TemplatedEmail $email
    ): void {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getRawId(),
            $user->getEmail(),
            [
                'id' => $user->getId()
            ]
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }

    public function handleEmailConfirmation(
        Request $request,
        User $user
    ): void {
        $this->verifyEmailHelper
            ->validateEmailConfirmationFromRequest(
                $request,
                $user->getRawId(),
                $user->getEmail()
            );

        $user->setIsEmailVerified(true);

        $this->userRepository->add($user);
        $this->userRepository->save();
    }
}
