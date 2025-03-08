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
    private $s3;

    public function __construct() {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);
    }

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
        return view('livewire.posts-manager');
    }
}