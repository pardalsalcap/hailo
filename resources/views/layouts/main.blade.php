<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    {{ Vite::useHotFile(public_path('hailo.hot'))
            ->useBuildDirectory('hailo')
            ->withEntryPoints(['resources/css/hailo.css', 'resources/js/hailo.js']) }}
    @stack('styles')


    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;
    </script>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
    />
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&family=Nunito:wght@400;700&display=swap" rel="stylesheet">

</head>
<body class="h-full">
<div class="xl:bg-red-600 lg:bg-gray-600 md:bg-green-600 sm:bg-yellow-900 bg-indigo-600;">
    <div class="hidden xl:block">XL</div>
    <div class="hidden lg:block xl:hidden">LG</div>
    <div class="hidden md:block lg:hidden xl:hidden">MD</div>
    <div class="hidden sm:block md:hidden lg:hidden xl:hidden">SM</div>
    <div class="sm:hidden md:hidden lg:hidden xl:hidden">XS</div>
</div>
<div class="font-sans text-gray-900 antialiased">
    <div x-data="{ mobileSidebar: false }">
        <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
        <x-hailo::sidebar-mobile/>
        <x-hailo::sidebar-desktop/>

        <div class="xl:pl-72">
            <div
                class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 xl:px-8">
                <button x-on:click="mobileSidebar = ! mobileSidebar" type="button"
                        class="-m-2.5 p-2.5 text-gray-700 xl:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <x-icon-menu class="h-6 w-6"/>
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <livewire:search-bar />
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <x-hailo::header-notifications />
                        <!-- Separator -->
                        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true"></div>
                        <livewire:profile-menu/>

                    </div>
                </div>
            </div>

            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    <!-- Your content -->
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</div>
</body>
</html>
