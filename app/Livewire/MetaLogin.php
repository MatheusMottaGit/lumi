<?php

namespace App\Livewire;

use Livewire\Component;

class MetaLogin extends Component
{
    public function getFacebookUserId() {}
    
    public function render()
    {
        return view('livewire.meta-login')->extends('components.layouts.auth');
    }
}
