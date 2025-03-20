<div class="w-full">
    <div x-data="{ showToast: false, message: '' }" x-on:notify.window="showToast = true; message = $event.detail; setTimeout(() => showToast = false, 3000)">
        <div x-show="showToast" class="flex items-center gap-2 fixed bottom-4 right-4 w-72 bg-gray-900 text-gray-100 px-4 py-3 rounded-md shadow-md transition-opacity">
            <img src="{{ url('svg/check.svg') }}" class="w-5 h-5">
            <span x-text="message"></span>
        </div>
    </div>

    <div x-data="{ showError: false, errorMessage: '' }" x-on:error.window="showError = true; errorMessage = $event.detail; setTimeout(() => showError = false, 3000)">
        <div x-show="showError" class="flex items-center gap-2 fixed bottom-4 right-4 w-72 bg-red-600 text-white px-4 py-3 rounded-md shadow-md transition-opacity">
            <img src="{{ url('svg/error.svg') }}" class="w-5 h-5">
            <span x-text="errorMessage"></span>
        </div>
    </div>

    <header class="w-full flex items-center justify-start gap-4 border-b border-b-gray-800 bg-gray-900/20 px-4 py-3">
        <img src="{{ url('svg/instagram.svg') }}" class="w-8 h-8">

        <div class="space-y-0.5">
            <h2 class="text-xl text-gray-100 font-semibold">Post on Instagram</h2>
            <p class="text-md text-gray-500">Just finish the last details to get your post done!</p>
        </div>
    </header>

    <div class="w-full px-4 mt-4 pb-4">
        <div class="p-4 rounded-xl bg-gray-900/30 border border-dashed border-gray-800 shadow-md flex flex-col items-center justify-center w-full h-72">
            <button type="button" wire:click="showUploadedFiles"
                class="cursor-pointer px-4 py-2 flex items-center gap-2 bg-sky-800 text-gray-200 rounded-lg hover:bg-sky-900 transition">
                See files <img src="{{ url('svg/search.svg') }}" class="w-5 h-5">
            </button>
        </div>

        @if ($openImagesModal)
            <div class="bg-black/40 fixed w-full h-full inset-0 flex justify-center items-center z-50">
                <div class="relative bg-gray-950 w-11/12 max-w-4xl h-3/4 rounded-lg border border-gray-800 p-6 shadow-lg flex flex-col">
                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-300">Organize carousel order</h2>
                        <p class="text-gray-400 text-sm mt-1">
                            Choose the images to define their order.
                        </p>
                    </div>

                    <div class="flex-1 overflow-auto grid grid-cols-3 md:grid-cols-4 gap-4">
                        @foreach ($splittedImagesPreview as $image)
                            <div class="relative cursor-pointer" wire:click="toggleSelection('{{ $image }}')">
                                <img src="{{ $image }}" class="w-full h-32 object-cover rounded-lg border {{ in_array($image, $imageOrder) ? 'border-sky-500' : 'border-gray-700' }}">

                                <div class="absolute top-2 right-2">
                                    <input type="checkbox" wire:model="imageOrder" value="{{ $image }}" class="w-5 h-5 text-sky-600 bg-gray-900 border-gray-700 rounded-lg focus:ring-2">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <button type="button" wire:click="postInstagramCarousel" class="w-full cursor-pointer px-4 py-2 bg-sky-800 flex items-center justify-center gap-2 text-gray-200 rounded-md hover:bg-sky-950 transition" wire:loading.class="opacity-50 cursor-not-allowed" wire:loading.attr="disabled">
                            <img src="{{ url('svg/loader-circle.svg') }}" class="w-5 h-5 animate-spin" wire:loading wire:target="postInstagramCarousel">
                            <span wire:loading.remove>Post to Instagram</span>
                            <img src="{{ url('svg/send.svg') }}" class="w-5 h-5" wire:loading.remove>
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>