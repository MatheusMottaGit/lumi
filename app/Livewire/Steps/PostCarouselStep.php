<?php

namespace App\Livewire\Steps;

use Aws\S3\S3Client;
use Http;
use Livewire\Component;

class PostCarouselStep extends Component
{
    public $splittedImagesPreview = [];
    public $imageOrder = [];
    public $openImagesModal = false;
    public $chatCompletionResponse = "";
    protected $listeners = ['generatedCompletion' => 'handleAIResponse'];
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

    public function handleAIResponse($response) {
        $this->chatCompletionResponse = $response;
    }
    
    public function showUploadedFiles() {
        $this->openImagesModal = true;
        
        try {
            $images = $this->s3->listObjectsV2([
                'Bucket' => env("AWS_BUCKET"),
                'Prefix' => 'lumi-posts/'
            ]);

            foreach (array_slice($images['Contents'], 1) as $img) {
                $this->splittedImagesPreview[] = env("AWS_URL") . $img['Key'];
            }
        } catch (Aws\S3\Exception\S3Exception $e) {
            dd($e);
        }

        // dd($this->splittedImagesPreview);
    }

    public function toggleSelection($image) {
        if (in_array($image, $this->imageOrder)) {
            $this->imageOrder = array_diff($this->imageOrder, [$image]);
        }else{
            $this->imageOrder[] = $image;
        }
    }

    public function postInstagramCarousel() {
        $itemsID = [];

        // create an item container
        foreach ($this->imageOrder as $image) {
            $response = Http::post(env("GRAPH_API_URI") . "/" . env("GRAPH_USER_ID") . "/media", [
                'is_carousel_item' => true,
                'image_url' => $image,
                'access_token' => env("GRAPH_ACCESS_TOKEN")
            ]);

            if ($response->successful()) {
                $itemsID[] = $response->json()['id'];
            }else{
                dd("Error on creating carousel.", $response->json());
            }
        }

        // create carousel container with items
        $containerResponse = Http::post(env("GRAPH_API_URI") . "/" . env("GRAPH_USER_ID") . "/media", [
            'media_type' => 'CAROUSEL',
            'children' => $itemsID,
            'access_token' => env("GRAPH_ACCESS_TOKEN")
        ]);

        if (!$containerResponse->successful()) {
            dd("Error on creating carousel.", $response->json());
        }

        // publish complete carousel container
        $carouselResponse = Http::post(env("GRAPH_API_URI") . "/" . env("GRAPH_USER_ID") . "/media_publish", [
            'creation_id' => $containerResponse->json()['id'],
            'caption' => 'Apenas aguarde. 👀',
            'access_token' => env("GRAPH_ACCESS_TOKEN")
        ]);

        if ($carouselResponse->successful()) {
            dd("Carousel is now on your profile! Go check!");
        }else{
            dd("Error on creating carousel.", $carouselResponse->json());
        }
    }

    public function render()
    {
        return view('livewire.steps.post-carousel-step');
    }
}
