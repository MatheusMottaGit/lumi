<?php

namespace App\Livewire;

use Aws\S3\S3Client;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostsManager extends Component
{
    use WithFileUploads;

    public $canvaFiles = [];
    public $steps = 4;
    public $currentStep = 1;
    public $splitting = false;
    public $splittedImages = [];

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
        $this->splitting = true;

        $s3 = new S3Client([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
            ]
        ]);

        foreach ($this->canvaFiles as $file) {
            $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
            $fullFileWidth = imagesx($image);
            $fullFileHeight = imagesy($image);

            $imagesQuantity = 6;
            $eachImageWidth = $fullFileWidth / $imagesQuantity;

            for ($i=0; $i < $imagesQuantity; $i++) { 
                $cloned = imagecreatetruecolor($eachImageWidth, $fullFileHeight);
                imagecopy(
                    $cloned,
                    $image,
                    0,
                    0,
                    $eachImageWidth * $i,
                    0,
                    $eachImageWidth,
                    $fullFileHeight
                );

                ob_start();
                imagepng($cloned);
                $stream = ob_get_clean();
                $filePath = "posts/split_{$i}.png";
                $this->splittedImages[] = $stream;

                try {
                    $s3->putObject([
                        'Bucket' => env("AWS_BUCKET"),
                        'Key' => $filePath,
                        'Body' => $stream,
                        'ACL' => 'public-read'
                    ]);

                    dd("upload worked!");
                } catch (Aws\S3\Exception\S3Exception $e) {
                    dd($e);
                }

                imagedestroy($cloned);
            }
            imagedestroy($image);
            $this->splitting = false;
        }
    }

    public function generatePostSubtitle() {}

    public function postInstagramCarousel() {}

    public function render()
    {
        return view('livewire.posts-manager');
    }
}