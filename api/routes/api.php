<?php

use App\Http\Controllers\Api\AwsS3Controller;
use App\Http\Controllers\Api\CarouselPostController;
use App\Http\Controllers\Api\ChatCompletionController;
use Illuminate\Support\Facades\Route;

Route::post('/upload', [AwsS3Controller::class, 'handleUploadS3']);
Route::post('/caption/completion', [ChatCompletionController::class, 'generatePostCaption']);
Route::post('/post/carousel', [CarouselPostController::class, 'postInstagramCarousel']);
