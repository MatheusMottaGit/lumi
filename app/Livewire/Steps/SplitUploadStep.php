<?php

namespace App\Livewire\Steps;
use Aws\S3\S3Client;
use Livewire\Component;

class SplitUploadStep extends Component
{
    public $canvaFiles = [];
    public $imagesNumber = "";
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

    public function splitUploadS3CanvaFile() {
        $this->dispatch('startSplitting');

        foreach ($this->canvaFiles as $file) {
            $image = imagecreatefromstring(file_get_contents($file->getRealPath()));
            $fullFileWidth = imagesx($image);
            $fullFileHeight = imagesy($image);

            $imagesQuantity = (int) $this->imagesNumber;
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
                $filePath = "lumi-posts/split_{$i}.png";
                
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

        $this->dispatch('notify', 'Splitting completed!');
    }

    public function mount($canvaFiles) {
        $this->canvaFiles = $canvaFiles;
    }
    
    public function render()
    {
        return view('livewire.steps.split-upload-step');
    }
}
