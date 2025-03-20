@extends('components.layouts.app')

@section("manager")
    <div class="flex items-center gap-4 w-full px-6">
        <img class="w-10 h-10 rounded-full" src="">
        <div class="font-medium text-gray-200">
            <div>Jese Leos</div>
            <div class="text-sm text-gray-700">Joined in August 2014</div>
        </div>
    </div>

    <form wire:submit.prevent="postInstagramCarousel" class="flex flex-col justify-center items-center w-2/4">
        <div class="bg-gray-950 border border-gray-800 rounded-xl shadow-lg w-full flex flex-col items-center justify-center">
            @if ($currentStep === 1)
                <x-blade.steps.browse-file-step 
                    :canvaFiles="$canvaFiles" 
                />
            @elseif ($currentStep === 2)
                @livewire('steps.split-upload-step', [
                    'canvaFiles' => $canvaFiles
                ])
            @elseif ($currentStep === 3)
                @livewire('steps.chat-completion-step')
                
            @elseif ($currentStep === 4)
                @livewire('steps.post-carousel-step', [
                    'splittedImagesPreview' => $splittedImagesPreview, 
                    'imageOrder' => $imageOrder, 
                    'openImagesModal' => $openImagesModal
                ])
            @endif
        </div>

        <div class="flex items-center justify-end w-full gap-2">
            <button
            @disabled($currentStep === 1)
                type="button" 
                wire:click="prevStep" 
                class="disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer mt-4 px-4 py-2 bg-gray-900/20 border border-gray-800 text-gray-200 rounded-md hover:bg-gray-950"
            >
                Go back
            </button>

            <button
                @disabled($currentStep === 4)
                type="button" 
                wire:click="nextStep" 
                class="disabled:cursor-not-allowed disabled:opacity-50 cursor-pointer mt-4 px-4 py-2 bg-sky-900 text-gray-200 rounded-md hover:bg-sky-950"
            >
                Next
            </button>
        </div>
    </form>
@endsection