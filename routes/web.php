<?php

use App\Livewire\PostsManager;
use Illuminate\Support\Facades\Route;

Route::get("/manage", PostsManager::class);
