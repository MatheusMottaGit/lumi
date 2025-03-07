<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI;
use Validator;

class OpenAIChatController extends Controller
{
    private $openAIClient;

    public function __construct() {
        $this->openAIClient = OpenAI::client(env("OPENAI_API_KEY"));
    }

    public function generateChatCompletion(Request $request) {
        $validator = Validator::make($request->all(), [
            'prompt' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation error.'], 422);
        }

        $completion = $this->openAIClient->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => $request->prompt]
            ]
        ]);

        $chatCompletionResponse = $completion->choices[0]->message->content;

        return response()->json(['message' => $chatCompletionResponse], 200);
    }
}
