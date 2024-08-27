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
        OpenAiClient $openAiClient,
        string $projectDir
    ): Response {
        $prompt = file_get_contents($projectDir . '/src/DataFixtures/data/landing_project_content.txt');

        dump($prompt);

        $answer = null;
        if (!empty($prompt)) {
            $answer = $openAiClient->prompt($prompt);
        }

        if (is_array($answer)) {
            $answer = $answer['choices'][0]['message']['content'];
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
