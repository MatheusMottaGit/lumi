<?php

use App\Http\Controllers\Api\AwsS3Controller;
use App\Http\Controllers\Api\CarouselPostController;
use App\Http\Controllers\Api\ChatCompletionController;
use Illuminate\Support\Facades\Route;

Route::post('/split_upload', [AwsS3Controller::class, 'handleUploadS3']);
Route::get('/bucket/parts', [AwsS3Controller::class, 'showImages']);
Route::post('/caption/completion', [ChatCompletionController::class, 'generatePostCaption']);
Route::post('/post/carousel', [CarouselPostController::class, 'postInstagramCarousel']);
