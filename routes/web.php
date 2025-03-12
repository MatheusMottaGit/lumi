<?php

use App\Livewire\MetaLogin;
use App\Livewire\PostsManager;
use Illuminate\Support\Facades\Route;

Route::get("/manage", PostsManager::class);
Route::get("/", MetaLogin::class);
