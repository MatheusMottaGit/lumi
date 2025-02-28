<?php

namespace App\Livewire;

use Aws\S3\S3Client;
use Livewire\Component;
use Livewire\WithFileUploads;
use OpenAI;

class PostsManager extends Component
{
    use WithFileUploads;

    public $canvaFiles = [];
    public $steps = 4;
    public $currentStep = 4;
    public $prompt = "";
    public $chatCompletionResponse = "";
    public $splittedImagesPreview = [];
    public $openImagesModal = false;
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

    public function splitUploadS3CanvaFile() {
        $this->dispatch('startSplitting');

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
                $imageRealContent = ob_get_clean();
                $filePath = "posts/split_{$i}.png";
                
                try {
                    $this->s3->putObject([
                        'Bucket' => env("AWS_BUCKET"),
                        'Key' => $filePath,
                        'Body' => $imageRealContent,
                    ]);        
                } catch (Aws\S3\Exception\S3Exception $e) {
                    dd($e);
                }

                imagedestroy($cloned);
            }
            imagedestroy($image);
        }
        $this->dispatch('stopSplitting');
        dd("uploaded!");
    }

    public function showUploadedFiles() {
        $this->openImagesModal = true;
        
        try {
            $images = $this->s3->listObjectsV2([
                'Bucket' => env("AWS_BUCKET")
            ]);

            foreach (array_slice($images['Contents'], 1) as $img) {
                $this->splittedImagesPreview[] = env("AWS_URL") . $img['Key'];
            }
        } catch (Aws\S3\Exception\S3Exception $e) {
            dd($e);
        }

        // dd($this->splittedImagesPreview);
    }

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

    public function postInstagramCarousel() {}

    public function render()
    {
        return view('livewire.posts-manager');
    }
}