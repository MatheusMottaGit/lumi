<div class="w-full">
    <div x-data="{ showToast: false, message: '' }" x-on:notify.window="showToast = true; message = $event.detail; setTimeout(() => showToast = false, 3000)">
        <div x-show="showToast" class="flex items-center gap-2 fixed bottom-4 right-4 w-72 bg-gray-900 text-gray-100 px-4 py-3 rounded-md shadow-md transition-opacity">
            <img src="{{ url('svg/check.svg') }}" class="w-5 h-5">
            <span x-text="message"></span>
        </div>
    </div>    
    
    <header class="w-full flex items-center justify-start gap-4 border-b border-b-gray-800 bg-gray-900/20 px-4 py-3">
        <img src="{{ url('svg/split.svg') }}" class="w-8 h-8">

        <div class="space-y-0.5">
            <h2 class="text-xl text-gray-100 font-semibold">Split and upload file(s)</h2>
            <p class="text-md text-gray-500">See the preview of your pictures, and set it to splitting.</p>
        </div>
    </header>

    <div class="flex flex-col gap-4 mt-4 px-4"> 
        <input type="text" wire:model="imagesNumber" class="w-64 bg-gray-900/30 text-gray-400 text-base p-2 rounded-lg border border-gray-800 outline-none focus:ring-2 focus:ring-sky-700 transition" placeholder="Number of images (Max: 10)..."></input>

        @foreach ($canvaFiles as $file)
            <div class="bg-gray-900 p-3 rounded-xl border border-gray-800 shadow-md w-full h-full">
                <img src="{{ $file->temporaryUrl() }}" class="rounded-lg w-full h-auto object-cover">
            </div>
        @endforeach

        <div class="w-full pb-4">
            <button type="button" wire:click="splitUploadS3CanvaFile" class="w-full cursor-pointer px-4 py-2 bg-sky-800 flex items-center justify-center gap-2 text-gray-200 rounded-md hover:bg-sky-950 transition" wire:loading.class="opacity-50 cursor-not-allowed" wire:loading.attr="disabled">
                <img src="{{ url('svg/loader-circle.svg') }}" class="w-5 h-5 animate-spin" wire:loading wire:target="splitUploadS3CanvaFile">
                <img src="{{ url('svg/scissors.svg') }}" class="w-5 h-5" wire:loading.remove>
                <span wire:loading.remove>
                    Split ({{ count($canvaFiles) }})
                </span>
            </button>
        </div>
    </div>
</div>