<form wire:submit.prevent="postInstagramCarousel" class="flex flex-col justify-center items-center w-2/4">
    <div class="bg-gray-950 border border-gray-800 rounded-xl shadow-lg w-full flex flex-col items-center justify-center">
        @if ($currentStep === 1)
            <x-blade.browse-file />
        @endif

        @if ($currentStep === 2)
            <x-blade.split-image />
        @endif
        
        @if ($currentStep === 3)
            <x-blade.subtitle-chat />
        @endif

        @if ($currentStep === 4)
            <x-blade.post-instagram />
        @endif
    </div>
    
    <div class="flex items-center justify-end w-full gap-2">
        <button 
            type="button" 
            wire:click="prevStep" 
            disabled="{{ $currentStep === 1 }}"
            class="{{ $currentStep === 1 ? 'cursor-not-allowed opacity-50' : ''}} cursor-pointer mt-4 px-4 py-2 bg-gray-900/20 border border-gray-800 text-gray-200 rounded-md hover:bg-gray-950"
        >
            Go back
        </button>

        <button 
            type="button" 
            wire:click="nextStep" 
            class="cursor-pointer mt-4 px-4 py-2 bg-sky-900 text-gray-200 rounded-md hover:bg-sky-950"
        >
            {{ $currentStep === 4 ? 'Next' : 'Post to Instagram'}}
        </button>
    </div>
</form>   

{{-- Gere uma legenda para um post no Instagram sobre WebSockets, seguindo este estilo: um tom casual e engajador, explicando de forma simples o que são WebSockets e onde são usados. Inclua emojis para tornar o texto mais dinâmico e finalize incentivando o engajamento. Exemplo de estrutura desejada:

Comece com uma chamada envolvente para o leitor.
Explique brevemente o que são WebSockets.
Dê exemplos de uso comuns.
Destaque a importância para desenvolvedores.
Finalize com um CTA para acompanhar mais conteúdos sobre desenvolvimento web
Não seja tão prolixo, e não tão descontraído. Use emojis de forma pontual. --}}