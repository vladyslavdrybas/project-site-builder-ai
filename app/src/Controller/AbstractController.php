<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractController extends SymfonyAbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected UrlGeneratorInterface $urlGenerator,
        protected SerializerInterface $serializer
    ) {}

    protected function getUser(): ?User
    {
        $user = parent::getUser();

        if (null === $user) {
            return null;
        }

        return $this->entityManager->getRepository(User::class)->loadUserByIdentifier($user->getUserIdentifier());
    }
}
