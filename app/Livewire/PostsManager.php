<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class PostsManager extends Component
{
    use WithFileUploads;

    public $steps = 4;
    public $currentStep = 1;
    
    // step 1 and 2
    public $canvaFiles = [];

    // step 4
    public $splittedImagesPreview = [];
    public $openImagesModal = false;
    public $imageOrder = [];

    public function nextStep() {
        if ($this->currentStep < $this->steps) {
            $this->currentStep++;
        }
    }

    public function prevStep() {
        if($this->currentStep > 1) {
            $this->currentStep--;         
        }
    }

    public function handleCanvaFile() {
        $this->validate([
            'canvaFiles.*' => 'image|mimes:png,jpg,jpeg|max:1024'
        ]);
    }

    public function render()
    {
        return view('livewire.posts-manager')->extends("components.layouts.app");
    }
}