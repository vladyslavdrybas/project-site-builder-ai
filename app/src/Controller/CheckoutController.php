<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: "app")]
class CheckoutController extends AbstractController
{
    #[Route("/checkout", name: "_checkout", methods: ["GET"])]
    public function index(): Response
    {
        return $this->render(
            'checkout/index.html.twig',
            [
                'meta' => [
                    'title' => 'Prototyper|Checkout',
                ],
            ]
        );
    }
}
