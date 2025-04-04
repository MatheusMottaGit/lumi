<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;
use OpenAI;
use Illuminate\Support\Facades\Validator;

class ChatCompletionController extends Controller
{
    public function generatePostCaption(Request $request) {
        $messages = [
            'prompt.required' => 'The prompt field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'prompt' => 'required|string',
        ], $messages);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid data. Please check your input.',
                'errors' => $validator->errors()
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
                'caption' => $completion->choices[0]->message->content
            ]);
        }

        return response()->json([
            'message' => 'Failed to generate caption...'
        ]);
    }
}
