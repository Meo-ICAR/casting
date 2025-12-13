@php
    $viewData = $this->getViewData();
    $statuses = $viewData['statuses'] ?? [];
    $tasks = $viewData['tasks'] ?? [];
@endphp


    <div class="flex space-x-4 p-4">
        @foreach ($statuses as $statusKey => $statusName)
            <div class="w-1/3 bg-gray-100 dark:bg-gray-800 rounded-lg shadow-lg p-3">
                <h3 class="text-lg font-semibold mb-3 text-center">{{ $statusName }}</h3>

                <div id="column-{{ $statusKey }}"
                     class="space-y-3 min-h-[50px] column-drag-area">
                    @if(isset($tasks[$statusKey]) && $tasks[$statusKey]->count() > 0)
                        @foreach ($tasks[$statusKey] as $task)
                        <div wire:key="task-{{ $task->id }}"
                             data-id="{{ $task->id }}"
                             class="bg-white dark:bg-gray-700 p-4 rounded-md shadow cursor-grab hover:shadow-xl transition duration-150">
                            @if($task->relationLoaded('profile') && $task->profile)
                                <p class="font-bold">{{ $task->profile->stage_name ?? 'No name' }}</p>
                                <p class="text-sm text-gray-600">
                                    ID: {{ $task->id }},
                                    Status: {{ $task->status }},
                                    Profile: {{ $task->profile->id ?? 'none' }}
                                </p>
                            @else
                                <p class="text-sm text-gray-400">No profile data (ID: {{ $task->id }})</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-center text-sm text-gray-500">No tasks in this column</p>
                @endif
                </div>
            </div>
        @endforeach
    </div>
