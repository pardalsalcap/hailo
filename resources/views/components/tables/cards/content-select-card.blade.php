<div
    @class([
    'block rounded-lg bg-white shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]',
    'border-4 border-hailo-300' => $selected,
])>


    </a>
    <div class="p-6">
        <div
            class="text-lg font-medium">
            <a wire:click="select({{ $content->id }})" href="javascript:void(0)">
                {{ $content->title }}
            </a>
        </div>


    </div>
</div>
