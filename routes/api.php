<?php

use App\Http\Controllers\AWSStorageController;
use App\Http\Controllers\OpenAIChatController;
use Illuminate\Support\Facades\Route;

Route::get('/files/uploadSplit', [AWSStorageController::class, 'splitUploadS3File']);
Route::get('/files/list', [AWSStorageController::class, 'showUploadedFiles']);
Route::get('/chat/completion', [OpenAIChatController::class, 'generateChatCompletion']);
