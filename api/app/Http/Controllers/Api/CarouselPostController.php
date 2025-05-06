<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstagramPostRequest;
use Http;
use Log;
use App\Traits\ApiResponse;

class CarouselPostController extends Controller
{
    use ApiResponse;

    public function postInstagramCarousel(InstagramPostRequest $request) {
        if (!$request->validated()) {
            return $this->errorResponse('Invalid data! Please check your input.', 422, $request->errors());
        }

        $validated = $request->validate([ // for query params
            'access_token' => 'required|string',
            'instagram_id' => 'required|string',
        ]);

        $accessToken = $validated['access_token'];
        $instagramAccountId = $validated['instagram_id'];
        $itemsID = [];

        foreach ($request->imageOrder as $image) {
            $response = Http::post(env("GRAPH_API_URI") . "/" . $instagramAccountId . "/media", [
                'is_carousel_item' => true,
                'image_url' => $image,
                'access_token' => $accessToken
            ]);

            Log::debug($response);
            
            if ($response->successful()) {
                $itemsID[] = $response->json()['id'];
            } else {
                return $this->errorResponse('Failed to set the items for the carousel. Try again.', 400);
            }
        }

        $containerResponse = Http::post(env("GRAPH_API_URI") . "/" . $instagramAccountId . "/media", [
            'media_type' => 'CAROUSEL',
            'children' => $itemsID,
            'caption' => $request->chatCompletion,
            'access_token' => $accessToken
        ]);

        if (!$containerResponse->successful()) {
            return $this->errorResponse('Failed to create your carousel container.', 400);
        }

        $carouselResponse = Http::post(env("GRAPH_API_URI") . "/" . $instagramAccountId . "/media_publish", [
            'creation_id' => $containerResponse->json()['id'],
            'access_token' => $accessToken
        ]);

        if ($carouselResponse->successful()) {
            return $this->successResponse($carouselResponse['id'], 'Carousel posted successfully!', 201);
        } else {
            return $this->errorResponse('Error on posting the carousel...', 400);
        }
    }
}