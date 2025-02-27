<form wire:submit.prevent="postInstagramCarousel" class="flex flex-col justify-center items-center w-2/4">
    <div class="bg-gray-950 border border-gray-800 rounded-xl shadow-lg w-full flex flex-col items-center justify-center">
        @if ($currentStep === 1)
            <header class="w-full flex items-center justify-start gap-4 border-b border-b-gray-800 bg-gray-900/20 px-4 py-3">
                <img src="{{ url('svg/cloud-upload.svg') }}" class="h-8 w-8">
                
                <div class="space-y-0.5">
                    <h2 class="text-xl text-gray-100 font-semibold">Browse file(s)</h2>
                    <p class="text-md text-gray-500">Select and upload the file(s) of your choice</p>
                </div>
            </header>
            
            <div class="w-full p-4 space-y-3">
                <div class="border-dashed border bg-gray-900/20 hover:bg-gray-900/40 cursor-pointer border-gray-700 rounded-lg h-72 p-6 text-center flex items-center justify-center">
                    <label for="canvaFile">
                        <div class="flex flex-col items-center justify-center text-gray-500">
                            <img src="{{ url('svg/upload.svg') }}" class="w-6 h-6">
                            <p class="mt-2 text-lg">Choose a file or drag & drop it here</p>
                            <p class="text-lg text-gray-400">JPEG, PNG, PDG, and PDF formats, up to 50MB</p>
                        </div>
                        <input type="file" id="canvaFile" class="hidden" wire:model="canvaFiles" multiple>
                    </label>
                </div>

                @if (!empty($canvaFiles))
                    @foreach ($canvaFiles as $file)
                        <div class="flex flex-col gap-3">
                            <div class="w-full flex items-center bg-gray-900 gap-2 p-3 rounded-lg">
                                <div class="bg-sky-700 text-gray-200 px-2 py-1 rounded-md">PNG</div>
                                <span class="text-gray-500">{{ $file->getClientOriginalName() }}</span>  
                            </div>
                        </div>                     
                    @endforeach
                @else
                    <div class="w-full flex items-center gap-2 bg-gray-900 p-3 rounded-lg">
                        <img src="{{ url('svg/file-check-2.svg') }}">
                        <span class="text-gray-500 text-center">Your file(s) will appear right here.</span>  
                    </div>
                @endif
            </div>
        @endif

        @if ($currentStep === 2)
            <header class="w-full flex items-center justify-start gap-4 border-b border-b-gray-800 bg-gray-900/20 px-4 py-3">
                <img src="{{ url('svg/split.svg') }}" class="w-8 h-8">
                
                <div class="space-y-0.5">
                    <h2 class="text-xl text-gray-100 font-semibold">Split and upload file(s)</h2>
                    <p class="text-md text-gray-500">See the preview of your pictures, and set it to splitting.</p>
                </div>
            </header>
    
            <div class="flex flex-col gap-4 mt-4 px-4">
                @foreach ($canvaFiles as $file)
                    <div class="bg-gray-900 p-3 rounded-xl border border-gray-800 shadow-md w-full h-full">
                        <img src="{{ $file->temporaryUrl() }}" class="rounded-lg w-full h-auto object-cover">
                    </div>
                @endforeach
    
                <div class="w-full pb-4">
                    <button type="button" wire:click="splitUploadS3CanvaFile" class="w-full cursor-pointer px-4 py-2 bg-sky-800 flex items-center justify-center gap-2 text-gray-200 rounded-md hover:bg-sky-950 transition" wire:loading.class="opacity-50 cursor-not-allowed" wire:loading.attr="disabled">
                        <img src="{{ url('svg/loader-circle.svg') }}" class="w-5 h-5 animate-spin" wire:loading wire:target="splitUploadS3CanvaFile">
                        <img src="{{ url('svg/scissors.svg') }}" class="w-5 h-5" wire:loading.remove>
                        <span wire:loading.remove>Split ({{ count($canvaFiles) }})</span>
                    </button>
                </div>
            </div>
        @endif
        
        @if ($currentStep === 3)
            <header class="w-full flex items-center justify-start gap-4 border-b border-b-gray-800 bg-gray-900/20 px-4 py-3">
                <img src="{{ url('svg/text.svg') }}" class="w-8 h-8">
                
                <div class="space-y-0.5">
                    <h2 class="text-xl text-gray-100 font-semibold">Generate subtitles</h2>
                    <p class="text-md text-gray-500">Based on your post images, set a prompt for AI to create the subtitles.</p>
                </div>
            </header>
        
            <div class="w-full flex gap-3 mt-4 px-4 pb-4">
                <div class="p-3 rounded-xl bg-gray-900/20 border border-dashed border-gray-800 shadow flex items-center justify-center w-1/2">
                    <button type="button" wire:click="showUploadedFiles" class="cursor-pointer px-4 py-2 flex items-center justify-center gap-2 bg-sky-900 text-gray-200 rounded-md hover:bg-sky-950">
                        See files <img src="{{ url('svg/search.svg') }}" class="w-5 h-5">
                    </button>

                    @if (!empty($splittedImagesPreview))
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($splittedImagesPreview as $img)
                                @if (!empty($img))
                                    <img src="{{ $img }}" class="rounded-md w-20 h-20 object-cover">
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
        
                <div class="w-full flex flex-col gap-3">
                    <input wire:model="prompt" type="text" class="bg-gray-900/20 text-gray-200 text-base p-2 rounded-md border border-gray-800 outline-none" placeholder="Enter prompt...">
                    <textarea disabled value="{{ $chatCompletionResponse }}" class="cursor-not-allowed resize-none bg-gray-900/20 text-gray-200 text-base p-2 rounded-md border border-gray-800 outline-none" rows="8" placeholder="AI response..."></textarea>
                    <button type="button" wire:click="nextStep" class="cursor-pointer px-4 py-2 flex items-center justify-center gap-2 bg-sky-900 text-gray-200 rounded-md hover:bg-sky-950">
                        Generate <img src="{{ url('svg/wand-sparkles.svg') }}" class="w-5 h-5">
                    </button>
                </div>
            </div> 
        @endif
    </div>
    
    <div class="flex items-center justify-end w-full gap-2">
        <button type="button" wire:click="prevStep" class="cursor-pointer mt-4 px-4 py-2 bg-gray-900/20 border border-gray-800 text-gray-200 rounded-md hover:bg-gray-950">Go back</button>
        <button type="button" wire:click="nextStep" class="cursor-pointer mt-4 px-4 py-2 bg-sky-900 text-gray-200 rounded-md hover:bg-sky-950">Next</button>
    </div>
</form>   