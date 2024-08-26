<?php
declare(strict_types=1);

namespace App\Controller;

use App\OpenAi\Client\OpenAiClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(name: 'app')]
class HomeController extends AbstractController
{
    #[Route('/', name: '_homepage', methods: ['GET', 'OPTIONS', 'HEAD'])]
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

    #[Route('/prompt', name: '_prompt', methods: ['GET'])]
    public function prompt(
        Request $request,
        OpenAiClient $openAiClient
    ): Response {
        $prompt = $request->query->get('prompt');

        dump($prompt);

        $answer = null;
        if (null !== $prompt) {
            $answer = $openAiClient->prompt($prompt);
        }

        return $this->render(
            'prompt.html.twig',
            [
                'meta' => [
                    'title' => 'Prompt',
                ],
                'data' => [
                    'prompt' => $prompt,
                    'answer' => $answer,
                ],
            ]
        );
    }
}
