<?php

namespace App\Livewire;

use Aws\S3\S3Client;
use Http;
use Livewire\Component;
use Livewire\WithFileUploads;
use OpenAI;

class PostsManager extends Component
{
    use WithFileUploads;

    public $canvaFiles = [];
    public $steps = 4;
    public $currentStep = 1;
    public $prompt = "";
    public $chatCompletionResponse = "";
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

    public function splitUploadS3CanvaFile() {
        $this->dispatch('startSplitting');

        
        $this->dispatch('stopSplitting');
        // dd("uploaded!");
    }

    public function showUploadedFiles() {
        $this->openImagesModal = true;
    }

    public function generatePostSubtitle() {
        $this->dispatch("gerenatingCompletion");

        $this->dispatch("doneCompletion");
    }

    public function postInstagramCarousel() {
        // create an item container
        // create the carousel container
        // publish the carousel container
    }

    public function render()
    {
        return view('livewire.posts-manager');
    }
}