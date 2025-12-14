@php
    $viewData = $this->getViewData();
    $statuses = $viewData['statuses'] ?? [];
    $tasks = $viewData['tasks'] ?? [];
    $records = $this->getTableRecords();
    $grouped = $records->groupBy('status');
@endphp
<div class="flex space-x-4 p-4">
    @foreach ($statuses as $statusKey => $statusName)
        <div class="w-1/4 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-lg p-3">
            <h3 class="text-lg font-semibold mb-3 text-center">
                {{ $statusName }}
                <span class="text-sm font-normal">({{ $grouped->get($statusKey, collect())->count() }})</span>
            </h3>

            <div id="column-{{ $statusKey }}" class="space-y-3 min-h-[50px] column-drag-area">
                @foreach($grouped->get($statusKey, []) as $record)
                    <div wire:key="task-{{ $record->id }}"
                         class="bg-white dark:bg-gray-700 p-4 rounded-md shadow cursor-grab hover:shadow-xl transition duration-150">
                        @if($record->relationLoaded('profile') && $record->profile)
                            <p class="font-bold">{{ $record->profile->stage_name }}</p>
                            @if(isset($record->profile->slug_name))
                                <p class="text-sm text-gray-600">{{ $record->profile->slug_name }}</p>
                            @endif
                        @else
                            <p class="text-sm text-gray-400">No profile data</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
