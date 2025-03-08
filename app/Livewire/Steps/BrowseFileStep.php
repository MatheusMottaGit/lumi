<?php

namespace App\Livewire\Steps;
use Livewire\WithFileUploads;

use Livewire\Component;

class BrowseFileStep extends Component
{
    use WithFileUploads;
    
    public $canvaFiles = [];
    
    public function render()
    {
        return view('livewire.steps.browse-file-step');
    }
}
