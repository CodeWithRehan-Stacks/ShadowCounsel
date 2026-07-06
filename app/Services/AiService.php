<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Exception;

class AiService
{
    protected string $baseUrl = 'https://openrouter.ai/api/v1';

    /**
     * Send a conversation history to the OpenRouter API and return the response.
     *
     * @param array  $messages Array of ['role' => '...', 'content' => '...']
     * @param string $model    OpenRouter model identifier
     * @return string          The assistant's text response.
     * @throws Exception
     */
    public function sendMessage(array $messages, string $model = 'poolside/laguna-xs-2.1:free'): string
    {
        $apiKey = config('services.openrouter.api_key');

        if (empty($apiKey) || $apiKey === 'sk-or-v1-your-api-key-here') {
            throw new Exception('OpenRouter API key is not configured. Please set OPENROUTER_API_KEY in your .env file.');
        }

        try {
            $response = Http::withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'HTTP-Referer'  => config('app.url', 'http://localhost'),
                    'X-Title'       => config('app.name', 'ShadowAI'),
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ])
                ->timeout(120)          // 2 minutes for slow models
                ->connectTimeout(15)    // 15s to establish the connection
                ->retry(2, 2000)        // Retry once after 2 seconds on failure
                ->post("{$this->baseUrl}/chat/completions", [
                    'model'    => $model,
                    'messages' => $messages,
                    'max_tokens' => 2048,
                ]);

        } catch (ConnectionException $e) {
            throw new Exception(
                'Could not connect to the AI provider. Please check your internet connection or try again later. ' .
                '(Detail: ' . $e->getMessage() . ')'
            );
        }

        if ($response->failed()) {
            $body    = $response->json();
            $errMsg  = $body['error']['message'] ?? $response->body();
            $status  = $response->status();

            if ($status === 429) {
                throw new Exception('Rate limit reached for this model. Please wait a moment and try again.');
            }
            if ($status === 401 || $status === 403) {
                throw new Exception('Invalid or expired OpenRouter API key. Please update your OPENROUTER_API_KEY.');
            }
            if ($status === 404) {
                throw new Exception("The selected model \"{$model}\" is not available for chat. Please choose a different model from the selector.");
            }
            if ($status === 503) {
                throw new Exception('The AI model is currently unavailable. Please select a different model and try again.');
            }

            throw new Exception("AI Provider Error ({$status}): {$errMsg}");
        }

        $data = $response->json();

        $content = $data['choices'][0]['message']['content'] ?? null;

        if ($content === null) {
            throw new Exception('The AI returned an empty response. Please try again.');
        }

        return $content;
    }
}
