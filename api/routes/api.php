<?php

use App\Http\Controllers\Api\AwsS3Controller;
use App\Http\Controllers\Api\CarouselPostController;
use App\Http\Controllers\Api\ChatCompletionController;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/split_upload', [AwsS3Controller::class, 'handleUploadS3']);
Route::get('/bucket/parts', [AwsS3Controller::class, 'showImages']);
Route::post('/caption/completion', [ChatCompletionController::class, 'generatePostCaption']);
Route::post('/post/carousel', [CarouselPostController::class, 'postInstagramCarousel']);

Route::get('/facebook/redirect', [LoginController::class, 'redirect']);
Route::get('/facebook/callback', [LoginController::class, 'callback']);
Route::get('/instagram/account/{instagramPageId}', [LoginController::class, 'selectAccount']);
