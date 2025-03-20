<?php

namespace App\Livewire;

use Laravel\Socialite\Facades\Socialite;
use Livewire\Component;
use Session;

class MetaLogin extends Component
{
    public function facebookRedirect() {
        return Socialite::driver('facebook')->scopes(['email', 'public_profile'])->redirect();
    }

    public function handleFacebookLogin() {
        $facebookUser = Socialite::driver('facebook')->user();

        $user = [
            'name' => $facebookUser->name,
            'email' => $facebookUser->email,
            'avatar' => $facebookUser->avatar,
            'access_token' => $facebookUser->token
        ];

        Session::put("userData", $user);

        return redirect()->route('manage');
    }
    
    public function render()
    {
        return view('livewire.meta-login')->extends('components.layouts.auth');
    }
}
