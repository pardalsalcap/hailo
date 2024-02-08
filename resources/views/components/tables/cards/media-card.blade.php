<div
    class="block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
    <a href="{{ $media->getUrl() }}" target="_blank">
        @if ($media->is_image)

            <img
                class="rounded-t-lg object-cover h-48 w-96"
                src="{{ $media->getUrl() }}"
                alt="{{ $media->alt }}"/>

        @else
            <div class=" h-48 w-96 flex items-center justify-center">
                @svg('file', 'text-gray-200 inline-block w-24 h-24 mr-1')
            </div>
        @endif
    </a>
    <div class="p-6">
        <div
            class="text-lg font-medium">
            @if (empty($media->title))
                <span class="text-hailo-600">{{ __("hailo::medias.missing_title") }}</span>
            @else
                {{--
                @foreach (config('hailo.languages') as $iso=>$language)
                    @if ($media->getTranslation('title', $iso, false)=='')
                        <span class="text-hailo-600">{{ __("hailo::medias.missing_title") }} ({{ $language }})</span><br />
                    @else
                        {{ $media->getTranslation('title', $iso, false)}}<br />
                    @endif
                @endforeach
                --}}
                {{ $media->title }}
            @endif
        </div>
        <div
            class="mb-2 text-base font-medium leading-tight text-neutral-800 dark:text-neutral-50">
            @if (empty($media->alt))
                <span class="text-hailo-600">{{ __("hailo::medias.missing_alt") }}</span>
            @else
                {{ $media->alt }}
            @endif
        </div>

        <div class="group relative">
            <div class="absolute bottom-[calc(100%+0.5rem)] left-[50%] -translate-x-[50%] hidden group-hover:block w-auto">
                <div class="bottom-full right-0 rounded bg-black px-4 py-1 text-xs text-white whitespace-nowrap">
                    {{ $media->original }}
                    <svg class="absolute left-0 top-full h-2 w-full text-black" x="0px" y="0px" viewBox="0 0 255 255"
                         xml:space="preserve"><polygon class="fill-current" points="0,0 127.5,127.5 255,0"/></svg>
                </div>
            </div>
            <p class="flex align-middle wrap truncate hover:text-clip">
                Original: {{ $media->original }}
                <br/>
            </p>
        </div>

        <div class="group relative">
            <p class="flex align-middle">
                URL: <span class="truncate">{{ $media->getUrl() }}</span>
                <a class="clipboard" aria-label="copiado"  data-clipboard-text="{{ $media->getUrl() }}">
                    @svg('copy', 'inline-block w-5 h-5 ml-1')
                </a>
                <br/>
            </p>

        </div>


        @if ($media->is_image)
            <p class="flex align-middle">
                @svg('dimensions', 'inline-block w-5 h-5 mr-1') dimensiones: {{ $media->width }}x{{ $media->height }}px
                <br/>
            </p>
        @endif
        <p class="mb-4 flex align-middle @if ($media->weight>500000) text-hailo-600 @endif">
            @svg('weight', 'inline-block w-5 h-5 mr-1') peso: {{ $media->weightToHuman() }}
        </p>
        <div class="table-actions">
            @if ($table->getHasEditAction())
                <a wire:click="edit({{ $media->id }})" href="javascript:void(0)" class="table-action edit">
                    @svg('edit', 'h-4 w-4')
                </a>
            @endif
            @if ($table->getHasDeleteAction())
                <a wire:click="confirmDelete({{ $media->id }})" href="javascript:void(0)" class="table-action delete">
                    @svg('delete', 'h-4 w-4')
                </a>
            @endif
        </div>

    </div>
</div>
