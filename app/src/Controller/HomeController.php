<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: "app")]
class HomeController extends AbstractController
{
    #[Route("/", name: "_homepage", methods: ["GET", "OPTIONS", "HEAD"])]
    public function index(): Response
    {
        return $this->render(
            'index.html.twig',
            [
                'meta' => [
                    'title' => 'Prototyper',
                ],
            ]
        );
    }
}
