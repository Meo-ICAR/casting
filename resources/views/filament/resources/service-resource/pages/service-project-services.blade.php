
<x-filament::page>
    <x-filament::card>
        <div class="space-y-6">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">
                    Servizi Progetto per: {{ $this->record->name }}
                </h2>
            </div>

            {{ $this->table }}
        </div>
    </x-filament::card>
</x-filament::page>
