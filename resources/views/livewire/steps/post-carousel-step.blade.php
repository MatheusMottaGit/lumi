<div class="w-full">
    <header class="w-full flex items-center justify-start gap-4 border-b border-b-gray-800 bg-gray-900/20 px-4 py-3">
        <img src="{{ url('svg/instagram.svg') }}" class="w-8 h-8">

        <div class="space-y-0.5">
            <h2 class="text-xl text-gray-100 font-semibold">Post on Instagram</h2>
            <p class="text-md text-gray-500">Just finish the last details to get your post done!</p>
        </div>
    </header>

    <div class="w-full px-4 mt-4 pb-4">
        <div
            class="p-4 rounded-xl bg-gray-900/30 border border-dashed border-gray-800 shadow-md flex flex-col items-center justify-center w-full h-72">
            <button type="button" wire:click="showUploadedFiles"
                class="cursor-pointer px-4 py-2 flex items-center gap-2 bg-sky-800 text-gray-200 rounded-lg hover:bg-sky-900 transition">
                See files <img src="{{ url('svg/search.svg') }}" class="w-5 h-5">
            </button>

            @if ($openImagesModal)
                <div class="bg-black/40 fixed w-full h-full inset-0 flex justify-center items-center z-50">
                    <div
                        class="relative bg-gray-950 w-11/12 max-w-4xl h-3/4 rounded-lg border border-gray-800 p-6 shadow-lg overflow-auto">
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-300">Organize carousel order</h2>
                            <p class="text-gray-400 text-sm mt-1">
                                Choose the images to define their order.
                            </p>
                        </div>

                        <div class="grid grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach ($splittedImagesPreview as $image)
                                <div class="relative cursor-pointer" wire:click="toggleSelection('{{ $image }}')">
                                    <img src="{{ $image }}" class="w-full h-32 object-cover rounded-lg border {{ in_array($image, $imageOrder) ? 'border-sky-500' : 'border-gray-700' }}">

                                    <div class="absolute top-2 right-2">
                                        <input type="checkbox" wire:model="imageOrder" value="{{ $image }}" class="w-5 h-5 text-sky-600 bg-gray-900 border-gray-700 rounded-lg focus:ring-0">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 flex justify-center">
                            <button type="button" wire:click="$set('openImagesModal', false)" class="px-5 py-2 bg-sky-700 text-gray-200 font-medium rounded-lg hover:bg-sky-800 transition shadow-md">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>