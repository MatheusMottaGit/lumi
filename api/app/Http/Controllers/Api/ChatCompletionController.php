<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatCaptionRequest;
use OpenAI;

class ChatCompletionController extends Controller
{
    public function generatePostCaption(ChatCaptionRequest $request) {
        if (!$request->validated()) {
            return response()->json([
                'message' => 'Invalid data. Please check your input.',
                'errors' => $request->errors()
            ], 422);
        }

        $prompt = $request->prompt;

        $openAI = OpenAI::client(env("OPENAI_API_KEY"));

        $completion = $openAI->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'user', 
                    'content' => $prompt
                ]
            ],
        ]);

        if ($completion->choices[0]->message->content) {
            return response()->json([
                'data' => $completion->choices[0]->message->content,
                'message' => 'Caption generated successfully!'
            ], 201);
        }

        return response()->json([
            'error' => 'Failed to generate caption...'
        ], 400);
    }
}
