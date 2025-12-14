<div>
    <button
        wire:click="toggle"
        wire:loading.attr="disabled"
        class="flex items-center justify-center px-4 py-2 rounded-md font-medium shadow-sm transition-all duration-200 border w-full sm:w-auto
        {{ $isShortlisted
            ? 'bg-indigo-600 text-white border-transparent hover:bg-indigo-700'
            : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
        }}"
    >
        @if($isShortlisted)
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            In Shortlist
        @else
            <svg class="w-5 h-5 mr-2 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            Salva Profilo
        @endif
    </button>
</div>
