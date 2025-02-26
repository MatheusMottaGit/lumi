<form wire:submit.prevent="postInstagramCarousel" class="flex flex-col justify-center items-center w-3/4">
    @if ($currentStep === 1)
        <div class="bg-slate-950 border border-slate-800 rounded-xl shadow-lg p-4 w-full">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl text-slate-100 font-semibold">Select file</h2>
            </div>
            <p class="text-lg text-slate-500 mt-1">Select and upload the files of your choice</p>
            
            <div class="mt-4 border-dashed border-2 bg-slate-900/20 hover:bg-slate-900/40 cursor-pointer border-slate-700 rounded-lg h-52 p-6 text-center flex items-center justify-center">
                <label for="canvaFile">
                    <div class="flex flex-col items-center justify-center text-slate-500">
                        <img src="{{ url('svg/cloud-upload.svg') }}">
                        <p class="mt-2">Choose a file or drag & drop it here</p>
                        <p class="text-lg text-slate-400">JPEG, PNG, PDG, and MP4 formats, up to 50MB</p>
                    </div>
                    <input type="file" id="canvaFile" class="hidden" wire:model="canvaFiles" multiple>
                </label>
            </div>
            
            <div class="mt-4 flex items-center justify-between bg-slate-900/20 border border-slate-700 p-3 rounded-lg">
                <div class="flex items-center space-x-2">
                    @if ($canvaFiles)
                        @foreach ($canvaFiles as $file)
                            <div class="bg-blue-700 text-slate-200 text-lg px-2 py-1 rounded-md">PNG</div>
                            <span class="text-slate-200 text-lg">{{ $file->getClientOriginalName() }}</span>                            
                        @endforeach
                    @else
                        <div class="w-full flex items-center justify-center gap-2">
                            <img src="{{ url('svg/file-check-2.svg') }}">
                            <span class="text-slate-500 text-center">Your file will appear right here.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($currentStep === 2)
        <div class="bg-slate-950 border border-slate-800 rounded-xl shadow-lg p-4 w-full h-full">
            <div class="flex justify-between items-center mb-1">
                <h2 class="text-2xl text-slate-100 font-semibold">Split and upload files</h2>
            </div>
            <p class="text-lg text-slate-500">See the preview of your pictures, and set it to splitting.</p>

            <div class="grid {{ count($canvaFiles) > 1 ? "grid-cols-2" : "grid-cols-1" }} gap-4 mt-4">
                @foreach ($canvaFiles as $file)
                    <div class="bg-slate-900 p-3 rounded-xl border border-slate-800 shadow-md w-full h-full">
                        <img src="{{ $file->temporaryUrl() }}" class="rounded-lg w-full h-auto object-cover">
                    </div>
                @endforeach

                @if($splitting)
                    <button disabled type="button" class="cursor-not-allowed col-span-2 px-4 py-2 bg-violet-800 opacity-50 flex items-center justify-center gap-2 text-slate-200 rounded-md">
                        <img src="{{ url('svg/loader-circle.svg') }}" class="w-5 h-5 animate-spin">
                    </button>
                @else
                    <button type="button" wire:click="splitUploadS3CanvaFile" class="cursor-pointer col-span-2 px-4 py-2 bg-violet-800 flex items-center justify-center gap-2 text-slate-200 rounded-md hover:bg-violet-950 transition">
                        <img src="{{ url('svg/scissors.svg') }}" class="w-5 h-5">
                        Split ({{ count($canvaFiles) }})
                    </button>
                @endif
            </div>
        </div>
    @endif
    
    @if ($currentStep === 3)
        <div class="bg-slate-950 border border-slate-800 rounded-2xl shadow-xl p-4 w-full h-full">
            <div class="flex justify-between items-center mb-2">
                <h2 class="text-xl text-white font-medium">Generate Subtitles</h2>
            </div>
            <p class="text-sm text-slate-400">
                Based on your post images, set a prompt for AI to create the subtitles.
            </p>
        
            <div class="flex gap-3 mt-5">
                <div class="p-3 rounded-xl bg-slate-900/20 border border-slate-800 shadow flex items-center justify-center w-1/2 text-white text-sm"></div>
        
                <div class="w-full flex flex-col gap-3">
                    <input type="text" class="bg-slate-900/20 text-slate-200 text-sm p-2 rounded-md border border-slate-700 outline-none" placeholder="Enter prompt">
                    <textarea class="bg-slate-900/20 text-slate-200 text-sm p-2 rounded-md border border-slate-700 outline-none" rows="8" placeholder="Generated subtitles"></textarea>
                    <button type="button" wire:click="nextStep" class="cursor-pointer px-4 py-2 bg-violet-900 text-slate-200 rounded-md hover:bg-violet-950">
                        Generate
                    </button>
                </div>
            </div>
        </div>    
    @endif
    
    @if ($currentStep === 4)@endif

    <div class="flex items-center justify-end w-full gap-2">
        <button type="button" wire:click="prevStep" class="cursor-pointer mt-4 px-4 py-2 bg-slate-900/20 border border-slate-700 text-slate-200 rounded-md hover:bg-slate-950">Go back</button>
        <button type="button" wire:click="nextStep" class="cursor-pointer mt-4 px-4 py-2 bg-violet-900 text-slate-200 rounded-md hover:bg-violet-950">Next</button>
    </div>
</form>   