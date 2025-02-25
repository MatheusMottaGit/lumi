<form wire:submit.prevent="postInstagramCarousel" class="flex flex-col justify-center items-center w-3/4">
    @if ($currentStep === 1)
        <div class="bg-slate-950 border border-slate-800 rounded-2xl shadow-lg p-6 w-full">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl text-slate-100 font-semibold">Select file</h2>
            </div>
            <p class="text-lg text-slate-500 mt-1">Select and upload the files of your choice</p>
            
            <div class="mt-4 border-dashed border-2 bg-slate-900/20 hover:bg-slate-900/40 cursor-pointer border-slate-700 rounded-lg h-52 p-6 text-center flex items-center justify-center">
                <label for="canvaFile">
                    <div class="flex flex-col items-center justify-center text-slate-500">
                        <img src="{{ url('svg/cloud-upload (1).svg') }}" alt="Upload Icon" class="text-slate-200">
                        <p class="mt-2">Choose a file or drag & drop it here</p>
                        <p class="text-lg text-slate-400">JPEG, PNG, PDG, and MP4 formats, up to 50MB</p>
                    </div>
                    <input type="file" id="canvaFile" class="hidden" wire:model="canvaFiles" multiple>
                </label>
            </div>
            
            <div class="mt-4 flex items-center justify-between bg-slate-900/20 border-2 border-slate-700 p-3 rounded-lg">
                <div class="flex items-center space-x-2">
                    @if ($canvaFiles)
                        @foreach ($canvaFiles as $file)
                            <div class="bg-blue-500 text-slate-200 text-lg px-2 py-1 rounded-md">PNG</div>
                            <span class="text-slate-200 text-lg">{{ $file->getClientOriginalName() }}</span>                            
                        @endforeach
                    @else
                        <span class="text-slate-500 text-center">Your file will appear right here.</span>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if ($currentStep === 2)
        <div class="bg-slate-950 border border-slate-800 rounded-2xl shadow-lg p-6 w-full">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl text-slate-100 font-semibold">Split and upload files</h2>
            </div>
            <p class="text-lg text-slate-500 mt-1">Select and upload the files of your choice</p>

            @foreach ($canvaFiles as $file)
                <img src="{{ $file->temporaryUrl() }}" class="rounded-lg">
            @endforeach
        </div>
    @endif
    
    @if ($currentStep === 3)@endif
    
    @if ($currentStep === 4)@endif


    <div class="flex items-center justify-end w-full gap-2">
        <button type="button" wire:click="prevStep" class="cursor-pointer mt-4 px-4 py-2 bg-slate-900/20 border border-slate-700 text-slate-200 rounded-md hover:bg-slate-950">Go back</button>
        <button type="button" wire:click="nextStep" class="cursor-pointer mt-4 px-4 py-2 bg-violet-900 text-slate-200 rounded-md hover:bg-violet-950">Next</button>
    </div>
</form>   