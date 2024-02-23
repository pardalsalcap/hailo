<div
    @class([
    'block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]',
    'border-4 border-hailo-300' => $selected,
])>
    <a wire:click="select({{ $media->id }})" href="javascript:void(0)">
        @if ($media->is_image)
            <img
                class="rounded-t-lg object-cover h-48 w-96"
                title="{{ $media->title }}"
                src="{{ $media->getUrl('cms') }}"
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

                {{ $media->title }}
            @endif
        </div>


    </div>
</div>
