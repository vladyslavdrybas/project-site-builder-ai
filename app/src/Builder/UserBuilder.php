<?php

declare(strict_types=1);

namespace App\Builder;

use App\Entity\User;
use App\Exceptions\AlreadyExists;
use App\Repository\UserRepository;
use App\Utility\RandomGenerator;
use InvalidArgumentException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function strlen;

class UserBuilder implements IEntityBuilder
{
    public function __construct(
        protected readonly UserPasswordHasherInterface $passwordHasher,
        protected readonly RandomGenerator $randomGenerator,
        protected readonly UserRepository $userRepository
    ) {}

    public function base(
        string $email,
        string $password,
        ?string $username = null
    ): User {
        if (strlen($email) < 6) {
            throw new InvalidArgumentException('Invalid email length. Expect string length greater than 5.');
        }

        $exist = $this->userRepository->findByEmail($email);
        if ($exist instanceof User) {
            throw new AlreadyExists('Such a user already exists.');
        }

        $user = new User();

        $user->setEmail(trim($email));
        $user->setPassword($this->hashPassword($user, $password));

        if (null === $username) {
            $rndGen = new RandomGenerator();
            $user->setUsername($rndGen->uniqueId('u'));
        }

        return $user;
    }

    public function hashPassword(User $user, string $password): string
    {
        return $this->passwordHasher->hashPassword(
            $user,
            trim($password)
        );
    }
}
