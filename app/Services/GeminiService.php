<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';
    }

    public function sendMessage($message)
    {
        // Define custom responses
        $customResponses = [
            'create you' => 'A software developer Mr Muhammad Irfan created me. He is a skilled professional who did his bachelor’s from NUML University.',
            'who make you' => 'A software developer Mr Muhammad Irfan created me. He is a skilled professional who did his bachelor’s from NUML University.',
            'hello' => 'Assalamo alaikum!',
            'hi' => 'Assalamo alaikum!',
            'hey' => 'Assalamo alaikum!',
            'Assalamo alaikum' =>'Walaikum salam wa rahmatullah wabarakatuhu',
            'how are you' => 'I am just a program, but I am here to assist you!',
        ];

        // Check for custom responses
        foreach ($customResponses as $keyword => $response) {
            if (stripos($message, $keyword) !== false) {
                return $response;
            }
        }

        // Make API call if no custom response
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . 'gemini-1.5-flash-latest:generateContent?key=' . $this->apiKey, [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $message
                            ]
                        ]
                    ]
                ]
            ]);

            // Access the response text
            $responseData = $response->json();

            // Extract the generated content
            if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                return $responseData['candidates'][0]['content']['parts'][0]['text'];
            }

            return 'No response from model.';
        } catch (\Exception $e) {
            // Handle any exceptions and return an error message
            return 'Error: ' . $e->getMessage();
        }
    }
}
