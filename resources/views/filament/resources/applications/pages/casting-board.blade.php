<x-filament-panels::page>
    <div
        class="flex overflow-x-auto h-full gap-4 pb-4 items-start"
        x-data="{
            updateStatus(id, status) {
                $wire.updateStatus(id, status);
            }
        }"
    >
        @foreach($this->getStatuses() as $statusKey => $config)
            <div
                class="min-w-[300px] w-[300px] rounded-xl p-3 flex flex-col gap-3 border {{ $config['color'] }} transition-colors"
                @dragover.prevent
                @drop="
                    let recordId = $event.dataTransfer.getData('text/plain');
                    updateStatus(recordId, '{{ $statusKey }}');
                "
            >
                <div class="font-bold text-gray-700 dark:text-gray-200 flex justify-between items-center px-1">
                    <span>{{ $config['title'] }}</span>
                    <span class="bg-white/50 text-xs px-2 py-1 rounded-full border border-gray-200">
                        {{ $this->getRecords()->get($statusKey)?->count() ?? 0 }}
                    </span>
                </div>

                <div class="flex flex-col gap-2 min-h-[100px]">
                    @foreach($this->getRecords()->get($statusKey) ?? [] as $record)
                        <div
                            draggable="true"
                            @dragstart="$event.dataTransfer.setData('text/plain', '{{ $record->id }}')"
                            class="bg-white dark:bg-gray-800 p-3 rounded-lg shadow-sm border border-gray-200 cursor-grab hover:shadow-md transition-all hover:border-indigo-400 group relative"
                        >
                            <div class="flex items-start gap-3">
                                <div class="shrink-0">
                                    @if($url = $record->profile->getFirstMediaUrl('headshots', 'thumb'))
                                        <img src="{{ $url }}" class="w-10 h-10 rounded-full object-cover border border-gray-100">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-400">
                                            {{ substr($record->profile->stage_name ?? 'A', 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                <div class="overflow-hidden">
                                    <div class="font-semibold text-sm text-gray-900 dark:text-gray-100 truncate">
                                        {{ $record->profile->stage_name ?? 'Nome non disp.' }}
                                    </div>
                                    <div class="text-xs text-gray-500 truncate">
                                        Ruolo: {{ $record->role->name }}
                                    </div>
                                    <div class="text-[10px] text-gray-400 mt-1 truncate">
                                        {{ $record->role->project->title }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
