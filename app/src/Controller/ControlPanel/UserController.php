<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Builder\UserBuilder;
use App\Constants\RouteRequirements;
use App\Entity\User;
use App\Form\CommandCenter\Profile\UserEditForm;
use App\Form\CommandCenter\Profile\UserPasswordChangeForm;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\ValueResolver\UserValueResolver;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    "/u/{user}",
    name: "cp_user",
    requirements: ['user' => RouteRequirements::UNIQUE_ID->value]
)]
class UserController extends AbstractControlPanelController
{
    #[Route(path: '', name: '_show', methods: ['GET'])]
    public function show(
        #[ValueResolver(UserValueResolver::class)] User $user
    ): Response {
        return $this->render(
            'control-panel/user/show.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    #[Route(path: '/edit', name: '_edit', methods: ['GET','POST'])]
    public function edit(
        #[ValueResolver(UserValueResolver::class)] User $user,
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher,
        UserBuilder $userBuilder
    ): Response {
        $emailBuffer = $user->getEmail();

        $userPasswordChangeForm = $this->createForm(UserPasswordChangeForm::class, $user);
        $userPasswordChangeForm->handleRequest($request);

        $userEditForm = $this->createForm(UserEditForm::class, $user);
        $userEditForm->handleRequest($request);

        try {
            if ($userEditForm->isSubmitted() && $userEditForm->isValid()) {
                dump($user);

                if ($emailBuffer !== $user->getEmail()) {
                    $user->setIsEmailVerified(false);
                }

                if (strlen($user->getUsername()) < 3 || strlen($user->getUsername()) > 21) {
                    throw new BadRequestHttpException('Username must be between 3 and 21 characters long');
                }

                if (null !== $user->getFirstname() && strlen($user->getFirstname()) > 100) {
                    throw new BadRequestHttpException('First name must be less than 100 characters long');
                }

                if (null !== $user->getLastname() && strlen($user->getLastname()) > 100) {
                    throw new BadRequestHttpException('Last name must be less than 100 characters long');
                }

                $userRepository->add($user);
                $userRepository->save();

                // sendPasswordChangeEmail
                $this->addFlash('success', 'Data changed.');
            }
        } catch (BadRequestHttpException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        try {
            if ($userPasswordChangeForm->isSubmitted() && $userPasswordChangeForm->isValid()) {
                $currentPassword = trim($userPasswordChangeForm->get('currentPassword')->getData());
                $newPassword = trim($userPasswordChangeForm->get('newPassword')->getData());
                $confirmPassword = trim($userPasswordChangeForm->get('confirmPassword')->getData());

                if ($newPassword !== $confirmPassword) {
                    throw new BadRequestHttpException('Passwords do not match');
                }

                if(!$userPasswordHasher->isPasswordValid($user, $currentPassword)) {
                    throw new BadRequestHttpException('Bad credentials');
                }

                if ($currentPassword !== $newPassword) {
                    $user->setPassword($userBuilder->hashPassword($user, $newPassword));

                    $userRepository->add($user);
                    $userRepository->save();
                }

                // sendPasswordChangeEmail
                $this->addFlash('success', 'Password changed.');
            }
        } catch (BadRequestHttpException $exception) {
            $this->addFlash('danger', $exception->getMessage());
        }

        return $this->render(
            'control-panel/user/edit.html.twig',
            [
                'user' => $user,
                'userPasswordChangeForm' => $userPasswordChangeForm,
                'userEditForm' => $userEditForm,
            ]
        );
    }


    #[Route(path: '/verify/email', name: '_verify_email', methods: ['GET'])]
    public function sendVerificationEmail(
        #[ValueResolver(UserValueResolver::class)] User $user,
        EmailVerifier $emailVerifier
    ): RedirectResponse {
        if (!$user->isEmailVerified()) {
            $this->sendValidationEmail($user, $emailVerifier);
        }

        return $this->redirectToRoute('cp_user_show', ['user' => $user->getUsername()]);
    }

    protected function sendValidationEmail(User $user, EmailVerifier $emailVerifier): void
    {
        // generate a signed url and email it to the user
        $emailVerifier->sendEmailConfirmation('security_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('noreply@prototyper.com', 'Prototyper Info'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('_notification/email/confirmation_email.html.twig')
        );
    }
}
