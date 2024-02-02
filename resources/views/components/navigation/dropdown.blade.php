<li>
    <div x-data="{ open: @if($navigationItem->checkIfChildrenIsActive()) true @else false @endif }">
        <button x-on:click="open = ! open" type="button"
                @class(
                    [
                    "bg-gray-50 text-hailo-400" => $navigationItem->checkIfChildrenIsActive(),
                    "text-gray-600 hover:text-hailo-400 hover:text-hailo-400 hover:bg-gray-50"=>!$navigationItem->checkIfChildrenIsActive(),
                    "flex items-center w-full text-left rounded-md p-2 gap-x-3 text-sm leading-6 font-semibold"
                    ]
                )
                aria-controls="sub-menu-1" aria-expanded="false">
            @svg($navigationItem->getIcon(), 'h-6 w-6 shrink-0')
            {{ $navigationItem->getLabel() }}
            @svg('chevron-down', 'text-gray-400 ml-auto h-5 w-5 shrink-0')
        </button>
        <ul x-cloak x-show="open === true" class="mt-1 px-2" id="sub-menu-1">
            @foreach($navigationItem->getChildren() as $child)
                <li>
                    <a href="{{ $child->getRoute() }}"
                       @class(
                           [
                           "text-hailo-600 bg-gray-50 " => $child->isActive(),
                           "text-gray-700 hover:bg-gray-50"=>!$child->isActive(),
                           "block rounded-md py-2 pr-2 pl-9 text-sm leading-6"
                           ]
                       )

                       @if ($child->getLivewire())
                           wire:navigate
                            @endif
                    >
                        {{ $child->getLabel() }}

                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</li>
