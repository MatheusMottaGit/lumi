<?php

namespace App\Livewire;

use Aws\S3\S3Client;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\Laravel\Facades\Image;

class PostsManager extends Component
{
    use WithFileUploads;

    public $canvaFiles = [];
    public $steps = 4;
    public $currentStep = 1;

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

    public function splitUploadS3CanvaFile($file) {
        $s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION')
        ]);

        $image = Image::make($file);
        $fullFileWidth = $image->width();
        $fullFileHeight = $image->height();

        $imagesQuantity = 6;
        $eachImageWidth = $fullFileWidth / $imagesQuantity;

        for ($i=0; $i < $imagesQuantity; $i++) { 
            $cloned = clone $image;
            $cloned->crop($eachImageWidth, $fullFileHeight, $eachImageWidth * $i, 0);
            $stream = $cloned->enconde('png');

            $filePath = "posts/slide_{$i}.png";

            $s3->putObject([
                ''
            ]);
        }
    }
    public function generatePostSubtitle() {}
    public function postInstagramCarousel() {}

    public function render()
    {
        return view('livewire.posts-manager');
    }
}
