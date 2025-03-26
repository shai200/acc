<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InterviewAnalysisController extends Controller
{
    private $openaiApiKey;
    private $systemPrompt = "You are an expert technical interviewer. Analyze the candidate's responses and provide:
    1. A follow-up question based on their answer
    2. Real-time feedback on their communication skills, technical knowledge, and problem-solving approach
    3. A score (0-100) based on their performance
    4. Notes on their strengths and areas for improvement
    Keep your responses concise and professional.";

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.api_key');
    }

    public function analyze(Request $request)
    {
        try {
            $transcription = $request->input('transcription');
            $context = $request->input('context');

            $messages = [
                [
                    'role' => 'system',
                    'content' => $this->systemPrompt
                ],
                [
                    'role' => 'user',
                    'content' => "Current interview context:
                    - Time remaining: {$context['timeRemaining']} seconds
                    - Questions answered: {$context['questionsAnswered']}
                    - Total questions: {$context['totalQuestions']}
                    - Current score: {$context['currentScore']}%
                    
                    Candidate's response: {$transcription}"
                ]
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->openaiApiKey,
                'Content-Type' => 'application/json'
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4-turbo-preview',
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);

            if ($response->successful()) {
                $aiResponse = $response->json()['choices'][0]['message']['content'];
                
                // Parse the AI response to extract different components
                $parsedResponse = $this->parseAIResponse($aiResponse);
                
                return response()->json([
                    'response' => $parsedResponse['question'],
                    'feedback' => $parsedResponse['feedback'],
                    'score' => $parsedResponse['score'],
                    'questionsAnswered' => $context['questionsAnswered'] + 1,
                    'totalQuestions' => $context['totalQuestions'] + 1
                ]);
            }

            throw new \Exception('Failed to get AI response');

        } catch (\Exception $e) {
            Log::error('Interview analysis error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to analyze interview response'
            ], 500);
        }
    }

    private function parseAIResponse($response)
    {
        // Split the response into sections
        $sections = explode("\n\n", $response);
        
        $parsed = [
            'question' => '',
            'feedback' => '',
            'score' => 0
        ];

        foreach ($sections as $section) {
            if (strpos($section, 'Question:') === 0) {
                $parsed['question'] = trim(substr($section, 9));
            } elseif (strpos($section, 'Feedback:') === 0) {
                $parsed['feedback'] = trim(substr($section, 9));
            } elseif (strpos($section, 'Score:') === 0) {
                $score = trim(substr($section, 6));
                $parsed['score'] = (int) preg_replace('/[^0-9]/', '', $score);
            }
        }

        return $parsed;
    }
}
