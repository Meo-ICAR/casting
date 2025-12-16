<x-filament-panels::page>
    <div class="space-y-6">
        {{ $this->infolist }}

        {{ $this->table }}

        <div class="space-y-6">
            @foreach ($this->getHeaderWidgets() as $widget)
                @livewire(\Livewire\Livewire::getAlias($widget), $this->getWidgetData(), key($widget))
            @endforeach
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($this->getVisibleFooterWidgets() as $widget)
                @livewire(\Livewire\Livewire::getAlias($widget), $this->getWidgetData(), key($widget))
            @endforeach
        </div>
    </div>
</x-filament-panels::page>
