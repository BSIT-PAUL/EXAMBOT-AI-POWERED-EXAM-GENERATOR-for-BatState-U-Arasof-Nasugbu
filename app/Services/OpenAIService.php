<?php 

namespace App\Services;

use GuzzleHttp\Client;

class OpenAIService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function classifyExamType($level, $items, $topicOutcomes, $educationLevel)
    {
        $prompt = "Given the following topic outcomes and Bloom's Taxonomy level, classify the appropriate exam type for college students. Avoid using 'Essay' unless the topic requires extensive written responses. The options are: Fill-in-the-blank, Matching, Multiple-Choice Questions, True/False, Short Answer, Completion, Essay.

Education Level: {$educationLevel}
Topic Outcomes: {$topicOutcomes}
Bloom's Taxonomy Level: {$level}
Number of Items: {$items}

Respond with one of the exam types listed above.";

        $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert in educational assessment.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'max_tokens' => 100,
            ],
        ]);

        $result = json_decode($response->getBody(), true);
        $examType = trim($result['choices'][0]['message']['content']);

        // Ensure the exam type fits the expected types
        $expectedTypes = [
            'Fill-in-the-blank', 'Matching', 'Multiple-Choice Questions', 'True/False', 
            'Short Answer', 'Completion', 'Essay'
        ];

        foreach ($expectedTypes as $type) {
            if (stripos($examType, $type) !== false) {
                return $type;
            }
        }

        // If no valid type is found, or if 'Essay' is selected inappropriately, default to 'Multiple-Choice Questions'
        return 'Multiple-Choice Questions';
    }
}
