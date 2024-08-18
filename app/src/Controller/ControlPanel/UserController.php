<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Builder\UserBuilder;
use App\Constants\RouteRequirements;
use App\Entity\User;
use App\Form\CommandCenter\Profile\UserPasswordChangeForm;
use App\Repository\UserRepository;
use App\ValueResolver\UserValueResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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

        $userPasswordChangeForm = $this->createForm(UserPasswordChangeForm::class, $user);
        $userPasswordChangeForm->handleRequest($request);

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
            ]
        );
    }
}
