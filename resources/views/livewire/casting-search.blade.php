<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Trova Talenti</h1>
        <p class="text-gray-500">Cerca nel database attori in tempo reale.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

        <div class="w-full lg:w-1/4 space-y-6">

            <div>
                <label class="block text-sm font-medium text-gray-700">Ricerca libera</label>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Es. 'Nuoto', 'Francese'..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Genere</label>
                <select wire:model.live="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Tutti</option>
                    <option value="male">Uomo</option>
                    <option value="female">Donna</option>
                    <option value="non_binary">Non-Binary</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Età Scenica</label>
                <div class="flex items-center space-x-2 mt-1">
                    <input wire:model.live.debounce.500ms="min_age" type="number" class="w-20 rounded-md border-gray-300">
                    <span class="text-gray-500">-</span>
                    <input wire:model.live.debounce.500ms="max_age" type="number" class="w-20 rounded-md border-gray-300">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Occhi</label>
                <select wire:model.live="eye_color" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Qualsiasi</option>
                    <option value="blue">Azzurri</option>
                    <option value="green">Verdi</option>
                    <option value="brown">Castani</option>
                </select>
            </div>

            <button wire:click="resetFilters" class="text-sm text-red-600 hover:underline">
                Resetta filtri
            </button>
        </div>

        <div class="w-full lg:w-3/4">

            <div wire:loading class="mb-4 text-indigo-600 text-sm font-semibold">
                Aggiornamento risultati...
            </div>

            @if($profiles->isEmpty())
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <p class="text-gray-500">Nessun attore trovato con questi criteri.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($profiles as $profile)
                        <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200 overflow-hidden border border-gray-100">
                            <div class="aspect-w-1 aspect-h-1 w-full bg-gray-200">
                                @if($profile->getFirstMediaUrl('headshots'))
                                    <img src="{{ $profile->getFirstMediaUrl('headshots', 'thumb') }}"
                                         alt="{{ $profile->stage_name }}"
                                         class="w-full h-64 object-cover object-top">
                                @else
                                    <div class="flex items-center justify-center h-64 text-gray-400">
                                        No Foto
                                    </div>
                                @endif
                            </div>

                            <div class="p-4">
                                <h3 class="text-lg font-bold text-gray-900">{{ $profile->stage_name ?? $profile->user->name }}</h3>
                                <p class="text-sm text-gray-600 mb-2">
                                    {{ $profile->age }} anni &bull; {{ $profile->city }}
                                </p>

                                <div class="flex flex-wrap gap-1 mb-3">
                                    @foreach(array_slice($profile->capabilities['skills'] ?? [], 0, 3) as $skill)
                                        <span class="px-2 py-0.5 rounded text-xs bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                </div>

                                <a href="#" class="block w-full text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Vedi Profilo
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $profiles->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
