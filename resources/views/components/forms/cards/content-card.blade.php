<div
    class="block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]">
    <div class="p-0 flex items-center bg-gray-800">
        @if ($mode=='multiple')
            <button class="block bg-yellow-400 px-2 py-2 text-black ring-0 ring-inset ring-gray-300 hover:bg-gray-400 hover:text-white focus:z-10"  wire:sortable-group.handle>
                @svg('drag', 'h-4 w-4')
            </button>
        @endif
        <a wire:click="removeContent('{{ $content->id }}', '{{ $input->getName() }}', '{{ $form->getName() }}', '{{ $mode }}')"
           href="javascript:void(0)"
           class="block bg-hailo-600 px-2 py-2 text-white ring-0 ring-inset ring-gray-300 hover:bg-gray-400 focus:z-10 ">
            @svg('delete', 'h-4 w-4')
        </a>

        <div
            class="text-lg ml-2 font-medium leading-tight text-white">
            <a href="{{ $content->getUrl() }}" target="_blank">
                {{ $content->title }}
            </a>

        </div>


    </div>
</div>
