<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI;

class ChatCompletionController extends Controller
{
    public function generatePostCaption(Request $request) {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $prompt = $request->prompt;

        $openAI = OpenAI::client(env("OPENAI_API_KEY"));

        $completion = $openAI->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ]
        ]);

        if ($completion->choices[0]->message->content) {
            return response()->json([
                'success' => true,
                'message' => 'Caption generated!',
                'data' => $completion->choices[0]->message->content
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to generate caption...'
        ]);
    }
}
