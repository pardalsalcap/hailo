<div

    class="block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
    <a href="{{ $media->getUrl() }}" target="_blank">
        @if ($media->is_image)
            <img
                class="rounded-t-lg object-cover w-full"
                title="{{ $media->title }}"
                src="{{ $media->getUrl('cms') }}"
                alt="{{ $media->alt }}"/>

        @else
            <div class=" h-48 w-96 flex items-center justify-center">
                @svg('file', 'text-gray-200 inline-block w-24 h-24 mr-1')
            </div>
        @endif
    </a>
    <div class="p-0 flex items-center bg-gray-800">
        @if ($mode=='multiple')
            <button class="text-white"  wire:sortable-group.handle>drag</button>
        @endif
        <a href="{{ route('hailo.medias') }}?register_id={{ $media->id }}&action=edit" target="_blank"
           class="block bg-lime-400 px-2 py-2 text-white ring-0 ring-inset ring-gray-300 hover:bg-gray-400 hover:text-white focus:z-10">
            @svg('edit', 'h-4 w-4')
        </a>

        <a wire:click="removeMedia('{{ $media->id }}', '{{ $input->getName() }}', '{{ $form->getName() }}', '{{ $mode }}')"
           href="javascript:void(0)"
           class="block bg-hailo-600 px-2 py-2 text-white ring-0 ring-inset ring-gray-300 hover:bg-gray-400 focus:z-10 ">
            @svg('delete', 'h-4 w-4')
        </a>

        <div
            class="text-lg ml-2 font-medium leading-tight text-white">
            @if (empty($media->alt))
                <span class="text-hailo-200">{{ __("hailo::medias.missing_alt") }}</span>
            @else
                {{ $media->alt }}
            @endif
        </div>


    </div>
</div>
