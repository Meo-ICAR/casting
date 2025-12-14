<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-x-4 md:space-y-0">
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Ricerca Casting</h2>
                <p class="text-sm text-gray-500">Filtra e consulta i profili attori visibili.</p>
            </div>

            <div class="flex items-center space-x-2">
                <x-filament::button
                    wire:click="resetFilters"
                    icon="heroicon-o-arrow-path"
                    color="gray"
                    size="sm"
                >
                    Reset filtri
                </x-filament::button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
            <!-- Filtri -->
            <div class="space-y-4">
                <x-filament::section>
                    <div class="space-y-4">
                        <x-filament::input.wrapper>
                            <x-filament::input.text
                                wire:model.live.debounce.300ms="search"
                                placeholder="Cerca per nome, skill..."
                                icon="heroicon-o-magnifying-glass"
                            />
                        </x-filament::input.wrapper>

                        <x-filament::input.wrapper>
                            <x-filament::input.select wire:model.live="gender">
                                <option value="">Tutti i generi</option>
                                <option value="male">Uomo</option>
                                <option value="female">Donna</option>
                                <option value="non_binary">Non-Binary</option>
                            </x-filament::input.select>
                        </x-filament::input.wrapper>

                        <div class="grid grid-cols-2 gap-4">
                            <x-filament::input.wrapper>
                                <x-filament::input.text
                                    wire:model.live.debounce.500ms="min_age"
                                    type="number"
                                    placeholder="Età min"
                                />
                            </x-filament::input.wrapper>

                            <x-filament::input.wrapper>
                                <x-filament::input.text
                                    wire:model.live.debounce.500ms="max_age"
                                    type="number"
                                    placeholder="Età max"
                                />
                            </x-filament::input.wrapper>
                        </div>

                        <x-filament::input.wrapper>
                            <x-filament::input.select wire:model.live="eye_color">
                                <option value="">Tutti i colori occhi</option>
                                <option value="blue">Azzurri</option>
                                <option value="green">Verdi</option>
                                <option value="brown">Castani</option>
                                <option value="hazel">Nocciola</option>
                                <option value="gray">Grigi</option>
                                <option value="black">Neri</option>
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>
                </x-filament::section>
            </div>

            <!-- Risultati -->
            <div class="lg:col-span-3">
                @if($profiles->isEmpty())
                    <x-filament::card>
                        <div class="p-6 text-center">
                            <x-heroicon-o-user-group class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Nessun profilo trovato</h3>
                            <p class="mt-1 text-sm text-gray-500">Modifica la ricerca o i filtri.</p>
                        </div>
                    </x-filament::card>
                @else
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($profiles as $profile)
                            <x-filament::card class="overflow-hidden">
                                <div class="aspect-w-3 aspect-h-4">
                                    @if($profile->getFirstMediaUrl('headshots'))
                                        <img
                                            src="{{ $profile->getFirstMediaUrl('headshots', 'thumb') }}"
                                            alt="{{ $profile->stage_name ?? $profile->user->name }}"
                                            class="h-64 w-full object-cover"
                                        >
                                    @else
                                        <div class="flex h-64 w-full items-center justify-center bg-gray-100 text-gray-400">
                                            <x-heroicon-o-user class="h-12 w-12" />
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4 space-y-2">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ $profile->stage_name ?? $profile->user->name }}
                                    </h3>

                                    <div class="flex items-center text-sm text-gray-500 space-x-2">
                                        <span>{{ $profile->age }} anni</span>
                                        <span class="text-gray-300">•</span>
                                        <span>{{ ucfirst($profile->gender) }}</span>
                                        @if($profile->city)
                                            <span class="text-gray-300">•</span>
                                            <span>{{ $profile->city }}</span>
                                        @endif
                                    </div>

                                    @if(!empty($profile->eye_color))
                                        <div class="text-sm text-gray-500">
                                            Occhi {{ ucfirst($profile->eye_color) }}
                                        </div>
                                    @endif

                                    @if(!empty($profile->capabilities['skills']))
                                        <div class="flex flex-wrap gap-1 pt-1">
                                            @foreach(array_slice($profile->capabilities['skills'], 0, 3) as $skill)
                                                <span class="inline-flex items-center rounded-full bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-600/20">
                                                    {{ $skill }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="pt-2">
                                        <x-filament::button
                                            href="{{ route('profile.show', $profile) }}"
                                            tag="a"
                                            size="sm"
                                            class="w-full justify-center"
                                        >
                                            Vedi profilo
                                        </x-filament::button>
                                    </div>
                                </div>
                            </x-filament::card>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $profiles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-filament-panels::page>
