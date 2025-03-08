<?php

namespace App\Livewire\Steps;

use Livewire\Component;

class ChatCompletionStep extends Component
{
    public $prompt = "";
    public $chatCompletionResponse = "";

    public function generatePostSubtitle() {
        $this->dispatch("gerenatingCompletion");

        $openAI = OpenAI::client(env("OPENAI_API_KEY"));

        $completion = $openAI->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => $this->prompt]
            ]
        ]);

        $this->chatCompletionResponse = $completion->choices[0]->message->content;

        // dd($this->chatCompletionResponse);

        $this->dispatch("doneCompletion");
    }
    
    public function render()
    {
        return view('livewire.steps.chat-completion-step');
    }
}
