<?php

namespace App\Livewire\Steps;

use Livewire\Component;
use OpenAI;

class ChatCompletionStep extends Component
{
    public $prompt = "";
    public $chatCompletionResponse = "";

    public function generatePostCaption() {
        $this->dispatch("gerenatingCompletion");

        $openAI = OpenAI::client(env("OPENAI_API_KEY"));

        $completion = $openAI->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => $this->prompt]
            ]
        ]);

        $this->chatCompletionResponse = $completion->choices[0]->message->content;
        
        $this->dispatch("doneCompletion");

        $this->dispatch('notify', 'Caption generated!');

        $this->emit('generatedCompletion', $this->chatCompletionResponse);
    }
    
    public function render()
    {
        return view('livewire.steps.chat-completion-step');
    }
}
