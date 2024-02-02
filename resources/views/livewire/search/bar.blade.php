<form class="flex flex-1" wire:submit="search" method="GET">
    @if ($show)
        <label for="search-field" class="sr-only">{{ __("hailo::hailo.search") }}</label>

        <button type="button" wire:click="search" class=" h-full w-5 text-gray-400">
            @svg('search', 'pointer-events-none h-full w-5 text-gray-400')
        </button>
        <div class="relative block h-full w-full ">
            <input wire:model="q"
                   id="search-field"
                   value="{{ $q }}"
                   class="border-0 py-0 pl-8 pr-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm h-full w-full"
                   placeholder="{{ __("hailo::hailo.search") }}..." type="search" name="search">
            @if (!empty($q))
                <button type="button" wire:click="clear" class="absolute inset-y-0 right-0 h-full w-5 text-gray-400">
                    @svg('close', 'pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-400')
                </button>
            @endif
        </div>
    @endif
</form>
