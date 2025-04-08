<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstagramPostRequest;
use Http;
use Log;

class CarouselPostController extends Controller
{
    public function postInstagramCarousel(InstagramPostRequest $request) {
        if (!$request->validated()) {
            return response()->json([
                'message' => 'Invalid data! Please check your input.',
                'error' => $request->errors()
            ], 422);
        }

        $itemsID = [];
        $accessToken = env("GRAPH_API_ACCESS_TOKEN");
        $instagramAccountId = env("GRAPH_INSTAGRAM_ACCOUNT_ID");

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
                return response()->json(['error' => 'Failed to set the items for the carousel. Try again.'], 400);
            }
        }

        $containerResponse = Http::post(env("GRAPH_API_URI") . "/" . $instagramAccountId . "/media", [
            'media_type' => 'CAROUSEL',
            'children' => $itemsID,
            'caption' => $request->chatCompletion,
            'access_token' => $accessToken
        ]);

        // Log::debug($containerResponse);

        if (!$containerResponse->successful()) {
            return response()->json(['error' => 'Failed to create your carousel container.'], 400);
        }

        $carouselResponse = Http::post(env("GRAPH_API_URI") . "/" . $instagramAccountId . "/media_publish", [
            'creation_id' => $containerResponse->json()['id'],
            'access_token' => $accessToken
        ]);

        if ($carouselResponse->successful()) {
            return response()->json(['data' => $carouselResponse['id'], 'message' => 'Carousel posted successfuly!'], 201);
        } else {
            return response()->json(['error' => 'Error on posting the carousel...'], 400);
        }
    }
}
