<div>
    <button wire:click="$set('showModal', true)" class="bg-indigo-600 text-white px-4 py-2 rounded">
        Invita a Casting
    </button>

    @if($showModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
                <h2 class="text-xl font-bold mb-4">Seleziona Ruolo</h2>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Progetto</label>
                    <select wire:model.live="selectedProjectId" class="mt-1 block w-full rounded-md border-gray-300">
                        <option value="">Seleziona un progetto...</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->title }}</option>
                        @endforeach
                    </select>
                </div>

                @if($selectedProjectId)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Ruolo</label>
                        <select wire:model="selectedRoleId" class="mt-1 block w-full rounded-md border-gray-300">
                            <option value="">Seleziona un ruolo...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="flex justify-end gap-2 mt-6">
                    <button wire:click="$set('showModal', false)" class="text-gray-500 px-4 py-2">Annulla</button>
                    <button wire:click="save"
                            class="bg-indigo-600 text-white px-4 py-2 rounded disabled:opacity-50"
                            @if(!$selectedRoleId) disabled @endif>
                        Conferma
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
