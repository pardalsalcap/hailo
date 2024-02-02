<!-- Profile dropdown -->
<div class="relative" x-data="{ openProfileDropDown : false}" @click.outside="openProfileDropDown = false">
    <button x-on:click="openProfileDropDown = ! openProfileDropDown" type="button"
            class="-m-1.5 flex items-center p-1.5" id="user-menu-button"
            aria-expanded="false" aria-haspopup="true">
        <span class="sr-only">{{ __("hailo::hailo.open_profile_menu") }}</span>
        <div
            class="group relative flex items-center justify-center h-8 w-8 text-base ring-1 ring-gray-300 font-bold text-white bg-hailo-300 rounded-full select-none">
            <span class="uppercase">{{ substr(auth()->user()->name,0,1) }}</span>
        </div>
        <span class="hidden lg:flex lg:items-center">
                <span class="ml-4 text-sm font-semibold leading-6 text-gray-900"
                      aria-hidden="true">{{ auth()->user()->name }}</span>
                                    <x-icon-chevron-down class="ml-2 h-5 w-5 text-gray-400"/>

              </span>
    </button>


    <div x-show="openProfileDropDown === true" x-cloak
         class="absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
         role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
         tabindex="-1">
        <!-- Active: "bg-gray-50", Not Active: "" -->
        <a wire:navigate href="{{ route("hailo.profile") }}" class="block px-3 py-1 text-sm leading-6 text-gray-900"
           role="menuitem"
           tabindex="-1" id="user-menu-item-0">{{ __("hailo::profile.profile") }}</a>
        <a href="{{ route('hailo.logout') }}" class="block px-3 py-1 text-sm leading-6 text-gray-900" role="menuitem"
           tabindex="-1" id="user-menu-item-1">{{ __("hailo::hailo.logout") }}</a>
    </div>
</div>
