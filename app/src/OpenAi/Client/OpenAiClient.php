<?php
declare(strict_types=1);

namespace App\OpenAi\Client;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenAiClient
{
    public function __construct(
        protected readonly HttpClientInterface $httpClient,
        protected readonly ParameterBagInterface $parameterBag
    ) {}

    public function prompt(string $prompt): array
    {
        $apiKey = $this->parameterBag->get('openai_api_secret_key');

        $response = $this->httpClient->request(
            'POST',
            'https://api.openai.com/v1/chat/completions',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4o-mini', // cheapest
//                    'model' => 'gpt-3.5-turbo-0125',
//                    'model' => 'gpt-3.5-turbo',
//                    'model' => 'gpt-4o-mini-2024-07-18',
                    'messages' => [
                        [
                            "role" => "system",
                            "content" => "You are a helpful assistant."
                        ],
                        [
                            "role" => "user",
                            "content" => $prompt
                        ],
                    ],
                ],
            ]
        );

        // Handle the response
        if ($response->getStatusCode() === 200) {
            $content = $response->toArray();

            dump($content);

            return $content;
        }

        $content = $response->getContent(false);

        throw new \Exception(
            'API request failed: ' . $response->getStatusCode()
            .
            '.'
            .
            $content
        );
    }
}
