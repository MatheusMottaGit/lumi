<?php

use App\Livewire\MetaLogin;
use App\Livewire\PostsManager;
use Illuminate\Support\Facades\Route;

Route::get("/manage", PostsManager::class)->name("manage");
Route::get("/", MetaLogin::class)->name("login");

Route::get('/auth/facebook', [MetaLogin::class, 'facebookRedirect'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [MetaLogin::class, 'handleFacebookLogin']);