<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarouselPostController extends Controller
{
    public function postInstagramCarousel(Request $request) {
        $messages = [
            'imageOrder.required' => 'The image order field is required.',
            'chatCompletion.required' => 'The chat completion field is required.',
        ];

        $validator = Validator::make($request->all(), [
            'access_token' => 'required',
            'imageOrder' => 'required|array',
            'chatCompletion' => 'required|text'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid data. Please check your input.',
                'errors' => $validator->errors()
            ], 422);
        }

        $itemsID = [];
        $accessToken = $request->access_token;
        $instagramAccountId = $this->findInstagramAccount($accessToken);

        foreach ($request->imageOrder as $image) {
            $response = Http::post(env("GRAPH_API_URI") . "/" . $instagramAccountId . "/media", [
                'is_carousel_item' => true,
                'image_url' => $image,
                'access_token' => $accessToken
            ]);

            if ($response->successful()) {
                $itemsID[] = $response->json()['id'];
            } else {
                return response()->json(['error' => 'Error on creating the carousel...'], 400);
            }
        }

        $containerResponse = Http::post(env("GRAPH_API_URI") . "/" . $instagramAccountId . "/media", [
            'media_type' => 'CAROUSEL',
            'children' => $itemsID,
            'access_token' => $accessToken
        ]);

        if (!$containerResponse->successful()) {
            return response()->json(['error' => 'Error on creating the carousel...'], 400);
        }

        $carouselResponse = Http::post(env("GRAPH_API_URI") . "/" . $instagramAccountId . "/media_publish", [
            'creation_id' => $containerResponse->json()['id'],
            'caption' => 'Apenas aguarde. 👀',
            'access_token' => $accessToken
        ]);

        if ($carouselResponse->successful()) {
            return response()->json(['message' => 'Carousel created successfuly!'], 200);
        } else {
            return response()->json(['error' => 'Error on creating the carousel...'], 400);
        }
    }

    private function findInstagramAccount($access_token) {
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
}
