<?php
declare(strict_types=1);

namespace App\Controller\ControlPanel;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/cp", name: "control_panel")]
abstract class AbstractControlPanelController extends SymfonyAbstractController
{
    public function __construct(
        protected readonly EntityManagerInterface $em,
    ) {}

    protected function getUser(): ?User
    {
        $user = parent::getUser();
        if (!$user instanceof User) {
            throw new AccessDeniedHttpException();
        }

        return $user;
    }
}
