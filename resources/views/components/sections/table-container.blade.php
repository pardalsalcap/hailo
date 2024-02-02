<section id="{{ $id }}" class="table-container overflow-auto">
    <h1>{{ $title }}</h1>
    <div class="bg-white py-8 rounded-b-xl border-t-2 border-hailo-400">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flow-root">
                <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-hailo::form-validation/>
</section>
