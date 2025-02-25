<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Request;

class PostsManager extends Component
{
    use WithFileUploads;

    public $canvaFile;
    public $canvaFilePreview;
    public $steps = 4;

    public function previewCanvaFile() {
        $this->validate([
            'canvaFilePreview' => 'image|mimes:png,jpg,jpeg|max:1024'
        ]);
    }

    public function uploadCanvaFile(Request $request) {}
    public function splitCanvaFile(Request $request) {}
    public function generatePostSubtitle(Request $request) {}
    public function postInstagramCarousel(Request $request) {}

    public function render()
    {
        return view('livewire.posts-manager');
    }
}
