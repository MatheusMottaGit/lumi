<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatCaptionRequest;
use Log;
use OpenAI;
use App\Traits\ApiResponse;

class ChatCompletionController extends Controller
{
    use ApiResponse;

    public function generatePostCaption(ChatCaptionRequest $request) {
        if (!$request->validated()) {
            return $this->errorResponse('Invalid data. Please check your input.', 422, $request->errors());
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
            return $this->successResponse($completion->choices[0]->message->content, 'Caption generated successfully!', 201);
        }

        return $this->errorResponse('Failed to generate caption...', 400);
    }
}
