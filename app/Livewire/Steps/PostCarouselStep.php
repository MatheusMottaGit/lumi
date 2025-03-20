<?php

namespace App\Livewire\Steps;

use Aws\S3\S3Client;
use Http;
use Livewire\Component;
use Session;

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
        $this->emit('loading', 'Carregando imagens...');

        try {
            $images = $this->s3->listObjectsV2([
                'Bucket' => env("AWS_BUCKET"),
                'Prefix' => 'lumi-posts/'
            ]);

            foreach (array_slice($images['Contents'], 1) as $img) {
                $this->splittedImagesPreview[] = env("AWS_URL") . $img['Key'];
            }

            $this->emit('loading', '');

        } catch (\Aws\S3\Exception\S3Exception $e) {
            $this->emit('error', 'Erro ao carregar as imagens.');
            return;
        }
    }

    public function toggleSelection($image) {
        if (in_array($image, $this->imageOrder)) {
            $this->imageOrder = array_diff($this->imageOrder, [$image]);
        } else {
            $this->imageOrder[] = $image;
        }
    }

    private function findInstagramAccountId($access_token) {
        $response = Http::get(env("GRAPH_API_URI") . "/me/accounts", [
            'access_token' => $access_token
        ]);

        if ($response->successful()) {
            $accounts = $response->json()['data'];
            foreach ($accounts as $account) {
                if ($account['instagram_business_account']) {
                    return $account['id'];
                }
            }
        }

        return null;
    }

    public function postInstagramCarousel() {
        $itemsID = [];
        $accessToken = Session::get("userData")['access_token'];
        $instagramAccountId = $this->findInstagramAccountId($accessToken);

        $this->emit('loading', 'Postando carrossel...');

        foreach ($this->imageOrder as $image) {
            $response = Http::post(env("GRAPH_API_URI") . "/" . $instagramAccountId . "/media", [
                'is_carousel_item' => true,
                'image_url' => $image,
                'access_token' => $accessToken
            ]);

            if ($response->successful()) {
                $itemsID[] = $response->json()['id'];
            } else {
                $this->emit('error', 'Erro ao criar o carrossel.');
                return;
            }
        }

        $containerResponse = Http::post(env("GRAPH_API_URI") . "/" . $instagramAccountId . "/media", [
            'media_type' => 'CAROUSEL',
            'children' => $itemsID,
            'access_token' => $accessToken
        ]);

        if (!$containerResponse->successful()) {
            $this->emit('error', 'Error on creating the carousel...');
            return;
        }

        $carouselResponse = Http::post(env("GRAPH_API_URI") . "/" . $instagramAccountId . "/media_publish", [
            'creation_id' => $containerResponse->json()['id'],
            'caption' => 'Apenas aguarde. 👀',
            'access_token' => $accessToken
        ]);

        if ($carouselResponse->successful()) {
            $this->dispatch('notify', 'Post submited! Go check on your profile.');
        } else {
            $this->emit('error', 'Erro ao publicar o carrossel.');
        }
    }

    public function render()
    {
        return view('livewire.steps.post-carousel-step');
    }
}