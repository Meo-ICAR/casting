<div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200 hover:shadow-md transition-shadow duration-200">
    <!-- Card Header -->
    <div class="p-4 border-b border-gray-100">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <img src="{{ $profilePhoto }}"
                     alt="{{ $displayName }}"
                     class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm"
                     onerror="this.onerror=null; this.src='{{ url('/images/default-avatar.png') }}';">
                <div>
                    <h3 class="font-medium text-gray-900">{{ $displayName }}</h3>
                    <p class="text-sm text-gray-500">{{ $role->name }}</p>
                </div>
            </div>

            <div class="flex space-x-1">
                @foreach($actions as $action)
                    {{ $action }}
                @endforeach
            </div>
        </div>
    </div>

    <!-- Card Body -->
    <div class="p-4 space-y-3">
        <div>
            <p class="text-xs font-medium text-gray-500">Progetto</p>
            <p class="text-sm text-gray-900">{{ $project->title }}</p>
        </div>

        @if($record->cover_letter)
            <div class="mt-2">
                <p class="text-xs font-medium text-gray-500">Messaggio</p>
                <p class="text-sm text-gray-700 line-clamp-3">{{ $record->cover_letter }}</p>
            </div>
        @endif

        <div class="pt-2 mt-2 border-t border-gray-100">
            <div class="flex items-center justify-between text-xs text-gray-500">
                <span title="Inviato il: {{ $appliedAt }}">
                    <x-filament::icon
                        icon="heroicon-o-calendar"
                        class="h-3.5 w-3.5 text-gray-400 inline-block mr-1"
                    />
                    {{ $appliedAt }}
                </span>
                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                    {{ $statusLabel }}
                </span>
            </div>
        </div>
    </div>
</div>
