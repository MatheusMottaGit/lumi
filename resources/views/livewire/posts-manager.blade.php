<form wire:submit.prevent="postInstagramCarousel" class="flex flex-col justify-center items-center w-2/4">
    <div
        class="bg-gray-950 border border-gray-800 rounded-xl shadow-lg w-full flex flex-col items-center justify-center">
        @if ($currentStep === 1)
            <header
                class="w-full flex items-center justify-start gap-4 border-b border-b-gray-800 bg-gray-900/20 px-4 py-3">
                <img src="{{ url('svg/cloud-upload.svg') }}" class="h-8 w-8">

                <div class="space-y-0.5">
                    <h2 class="text-xl text-gray-100 font-semibold">Browse file(s)</h2>
                    <p class="text-md text-gray-500">Select and upload the file(s) of your choice</p>
                </div>
            </header>

            <div class="w-full p-4 space-y-3">
                <div
                    class="border-dashed border bg-gray-900/20 hover:bg-gray-900/40 cursor-pointer border-gray-700 rounded-lg h-72 p-6 text-center flex items-center justify-center">
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
            <header
                class="w-full flex items-center justify-start gap-4 border-b border-b-gray-800 bg-gray-900/20 px-4 py-3">
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
                    <button type="button" wire:click="splitUploadS3CanvaFile"
                        class="w-full cursor-pointer px-4 py-2 bg-sky-800 flex items-center justify-center gap-2 text-gray-200 rounded-md hover:bg-sky-950 transition"
                        wire:loading.class="opacity-50 cursor-not-allowed" wire:loading.attr="disabled">
                        <img src="{{ url('svg/loader-circle.svg') }}" class="w-5 h-5 animate-spin" wire:loading
                            wire:target="splitUploadS3CanvaFile">
                        <img src="{{ url('svg/scissors.svg') }}" class="w-5 h-5" wire:loading.remove>
                        <span wire:loading.remove>Split ({{ count($canvaFiles) }})</span>
                    </button>
                </div>
            </div>
        @endif

        @if ($currentStep === 3)
            <header
                class="w-full flex items-center justify-start gap-4 border-b border-b-gray-800 bg-gray-900/20 px-4 py-3">
                <img src="{{ url('svg/text.svg') }}" class="w-8 h-8">

                <div class="space-y-0.5">
                    <h2 class="text-xl text-gray-100 font-semibold">Generate subtitles</h2>
                    <p class="text-md text-gray-500">Based on your post images, set a prompt for AI to create the subtitles.
                    </p>
                </div>
            </header>


            <div class="flex flex-col w-full gap-4 mt-4 px-4 pb-4">
                <textarea wire:model="prompt"
                    class="bg-gray-900/30 text-gray-400 text-base p-3 rounded-lg border border-gray-800 outline-none focus:ring-2 focus:ring-sky-700 transition"
                    placeholder="Enter prompt..."></textarea>

                <textarea disabled wire:model="chatCompletionResponse" placeholder="AI response..."
                    class="h-48 cursor-not-allowed resize-none bg-gray-900/30 text-gray-400 text-base p-3 rounded-lg border border-gray-800 outline-none"></textarea>

                <button type="button" wire:click="generatePostSubtitle"
                    class="cursor-pointer px-4 py-2 flex items-center justify-center gap-2 bg-sky-900 text-gray-200 rounded-lg hover:bg-sky-900 transition">
                    Generate <img src="{{ url('svg/wand-sparkles.svg') }}" class="w-5 h-5">
                </button>
            </div>
        @endif

        @if ($currentStep === 4)
            <header
                class="w-full flex items-center justify-start gap-4 border-b border-b-gray-800 bg-gray-900/20 px-4 py-3">
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
                                    <button type="button" wire:click="saveImageOrder" class="px-5 py-2 bg-sky-700 text-gray-200 font-medium rounded-lg hover:bg-sky-800 transition shadow-md">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <div class="flex items-center justify-end w-full gap-2">
        <button type="button" wire:click="prevStep" class="cursor-pointer mt-4 px-4 py-2 bg-gray-900/20 border border-gray-800 text-gray-200 rounded-md hover:bg-gray-950">
            Go back
        </button>

        <button type="button" wire:click="nextStep" class="cursor-pointer mt-4 px-4 py-2 bg-sky-900 text-gray-200 rounded-md hover:bg-sky-950">
            {{ $currentStep === 4 ? "Post to Instagram" : "Next" }}
        </button>
    </div>
</form>

{{-- Gere uma legenda para um post no Instagram sobre WebSockets, seguindo este estilo: um tom casual e engajador,
explicando de forma simples o que são WebSockets e onde são usados. Inclua emojis para tornar o texto mais dinâmico e
finalize incentivando o engajamento. Exemplo de estrutura desejada:

Comece com uma chamada envolvente para o leitor.
Explique brevemente o que são WebSockets.
Dê exemplos de uso comuns.
Destaque a importância para desenvolvedores.
Finalize com um CTA para acompanhar mais conteúdos sobre desenvolvimento web
Não seja tão prolixo, e não tão descontraído. Use emojis de forma pontual. --}}