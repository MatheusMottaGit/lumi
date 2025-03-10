<div class="w-full">
    <div x-data="{ showToast: false, message: '' }" x-on:notify.window="showToast = true; message = $event.detail; setTimeout(() => showToast = false, 3000)">
        <div x-show="showToast" class="flex items-center gap-2 fixed bottom-4 right-4 w-72 bg-gray-900 text-gray-100 px-4 py-3 rounded-md shadow-md transition-opacity">
            <img src="{{ url('svg/check.svg') }}" class="w-5 h-5">
            <span x-text="message"></span>
        </div>
    </div>

    <header class="w-full flex items-center justify-start gap-4 border-b border-b-gray-800 bg-gray-900/20 px-4 py-3">
        <img src="{{ url('svg/text.svg') }}" class="w-8 h-8">

        <div class="space-y-0.5">
            <h2 class="text-xl text-gray-100 font-semibold">Generate caption</h2>
            <p class="text-md text-gray-500">Based on your post images, set a prompt for AI to create the caption.</p>
        </div>
    </header>


    <div class="flex flex-col w-full gap-4 mt-4 px-4 pb-4">
        <textarea wire:model="prompt" class="bg-gray-900/30 text-gray-400 text-base p-3 rounded-lg border border-gray-800 outline-none focus:ring-2 focus:ring-sky-700 transition" placeholder="Enter prompt..."></textarea>

        <textarea disabled wire:model="chatCompletionResponse" placeholder="AI response..." class="h-48 cursor-not-allowed resize-none bg-gray-900/30 text-gray-400 text-base p-3 rounded-lg border border-gray-800 outline-none"></textarea>

        <button type="button" wire:click="generatePostCaption" class="w-full cursor-pointer px-4 py-2 bg-sky-800 flex items-center justify-center gap-2 text-gray-200 rounded-md hover:bg-sky-950 transition" wire:loading.class="opacity-50 cursor-not-allowed" wire:loading.attr="disabled">
            <img src="{{ url('svg/loader-circle.svg') }}" class="w-5 h-5 animate-spin" wire:loading wire:target="generatePostCaption">
            <span wire:loading.remove>Generate</span>
            <img src="{{ url('svg/wand-sparkles.svg') }}" class="w-5 h-5" wire:loading.remove>
        </button>
    </div>
</div>