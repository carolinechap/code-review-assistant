<?php

namespace App\Service;

use App\Dto\CodeAnalysisResponse;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MistralAIService
{

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private SerializerInterface $serializer,
        #[Autowire(env: 'MISTRAL_API_KEY')] private readonly string $apiKey,
        #[Autowire(env: 'MISTRAL_API_ENDPOINT')] private readonly string $apiEndpoint,
    ) {
    }

    public function generate(string $prompt): CodeAnalysisResponse
    {
        $payload = [
            'model' => 'devstral-small-latest',
            'response_format' => [
                'type' => 'json_object',
            ],
            'messages' => [
                [
                    'content' => $prompt,
                    'role' => 'user',
                ],
            ],
        ];


        $response = $this->httpClient->request(Request::METHOD_POST, $this->apiEndpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            'json' => $payload,
        ]);

        $responseData = $response->toArray();
        $contentJson = $responseData['choices'][0]['message']['content'];

        return $this->serializer->deserialize($contentJson, CodeAnalysisResponse::class, JsonEncoder::FORMAT);

    }
}
