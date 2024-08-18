<?php

namespace App\Controller;

use App\Builder\UserBuilder;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppFormLoginAuthenticator;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\ExpiredSignatureException;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route(name: "security")]
class RegistrationController extends AbstractController
{
    #[Route('/register', name: '_register')]
    public function register(
        Request $request,
        Security $security,
        UserRepository $userRepository,
        UserBuilder $userBuilder,
        EmailVerifier $emailVerifier
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userBuilder->base(
                $form->get('email')->getData(),
                $form->get('plainPassword')->getData()
            );

            $userRepository->add($user);
            $userRepository->save();

            $this->sendValidationEmail($user, $emailVerifier);
            // do anything else you need here, like send an email

            return $security->login($user, AppFormLoginAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: '_verify_email')]
    public function verifyUserEmail(
        Request $request,
        UserRepository $userRepository,
        EmailVerifier $emailVerifier
    ): Response {
        $id = $request->query->get('id');

        if (null === $id) {
            $this->addFlash('verify_email_error', 'Cannot find user to verify.');

            return $this->redirectToRoute('security_login');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            $this->addFlash('verify_email_error', 'Cannot find user to verify.');

            return $this->redirectToRoute('security_login');
        }


        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            if (!$user->isEmailVerified()) {
                $emailVerifier->handleEmailConfirmation($request, $user);
            }
        } catch (ExpiredSignatureException $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            $this->sendValidationEmail($user, $emailVerifier);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());
        } catch (\Exception $exception) {
            $this->addFlash('verify_email_error', 'Unknown validation email exception.');
        }

        if ($user->isEmailVerified()) {
            // @TODO Change the redirect on success and handle or remove the flash message in your templates
            $this->addFlash('success', 'Your email address has been verified.');
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
