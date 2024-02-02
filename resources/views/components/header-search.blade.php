<form class="relative flex flex-1" wire:submit="search" method="GET">
    <label for="search-field" class="sr-only">{{ __("hailo::hailo.search") }}</label>
    @svg('search', 'pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-400')
    <input id="search-field"
           class="block h-full w-full border-0 py-0 pl-8 pr-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm"
           placeholder="{{ __("hailo::hailo.search") }}..." type="search" name="search">
</form>
