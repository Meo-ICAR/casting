<x-filament-panels::page>
    <div class="space-y-6">
        <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-x-4 md:space-y-0">
            <h2 class="text-2xl font-bold tracking-tight">Casting Search</h2>

            <div class="flex items-center space-x-2">
                <x-filament::button
                    wire:click="resetFilters"
                    icon="heroicon-o-arrow-path"
                    color="gray"
                    size="sm"
                >
                    Reset Filters
                </x-filament::button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
            <!-- Filters -->
            <div class="space-y-4">
                <x-filament::section>
                    <x-filament::input.wrapper>
                        <x-filament::input.text
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search by name, skills..."
                            icon="heroicon-o-magnifying-glass"
                        />
                    </x-filament::input.wrapper>

                    <div class="mt-4 space-y-4">
                        <x-filament::input.wrapper>
                            <x-filament::input.select wire:model.live="gender">
                                <option value="">All Genders</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="non_binary">Non-binary</option>
                            </x-filament::input.select>
                        </x-filament::input.wrapper>

                        <div class="grid grid-cols-2 gap-4">
                            <x-filament::input.wrapper>
                                <x-filament::input.text
                                    wire:model.live.debounce.500ms="min_age"
                                    type="number"
                                    placeholder="Min age"
                                />
                            </x-filament::input.wrapper>

                            <x-filament::input.wrapper>
                                <x-filament::input.text
                                    wire:model.live.debounce.500ms="max_age"
                                    type="number"
                                    placeholder="Max age"
                                />
                            </x-filament::input.wrapper>
                        </div>

                        <x-filament::input.wrapper>
                            <x-filament::input.select wire:model.live="eye_color">
                                <option value="">All Eye Colors</option>
                                <option value="blue">Blue</option>
                                <option value="green">Green</option>
                                <option value="brown">Brown</option>
                                <option value="hazel">Hazel</option>
                                <option value="gray">Gray</option>
                            </x-filament::input.select>
                        </x-filament::input.wrapper>
                    </div>
                </x-filament::section>
            </div>

            <!-- Results -->
            <div class="lg:col-span-3">
                @if($profiles->isEmpty())
                    <x-filament::card>
                        <div class="p-6 text-center">
                            <x-heroicon-o-user-group class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No profiles found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
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
                                            alt="{{ $profile->stage_name }}"
                                            class="h-64 w-full object-cover"
                                        >
                                    @else
                                        <div class="flex h-64 w-full items-center justify-center bg-gray-100 text-gray-400">
                                            <x-heroicon-o-user class="h-12 w-12" />
                                        </div>
                                    @endif
                                </div>

                                <div class="p-4">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ $profile->stage_name ?? $profile->user->name }}
                                    </h3>

                                    <div class="mt-1 flex items-center text-sm text-gray-500">
                                        <span>{{ $profile->age }} years</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ ucfirst($profile->gender) }}</span>
                                    </div>

                                    @if(!empty($profile->eye_color))
                                        <div class="mt-1 text-sm text-gray-500">
                                            {{ ucfirst($profile->eye_color) }} eyes
                                        </div>
                                    @endif

                                    @if(!empty($profile->capabilities['skills']))
                                        <div class="mt-3 flex flex-wrap gap-1">
                                            @foreach(array_slice($profile->capabilities['skills'], 0, 3) as $skill)
                                                <span class="inline-flex items-center rounded-full bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-600/20">
                                                    {{ $skill }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="mt-4">
                                        <x-filament::button
                                            href="{{ route('profile.show', $profile) }}"
                                            tag="a"
                                            size="sm"
                                            class="w-full justify-center"
                                        >
                                            View Profile
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
