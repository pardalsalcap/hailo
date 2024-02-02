<li>
    <!-- Current: "bg-gray-50 text-indigo-600", Default: "text-gray-700 hover:text-indigo-600 hover:bg-gray-50" -->
    <a href="{{ $navigationItem->getRoute() }}"
       @if ($navigationItem->getLivewire())
           wire:navigate
       @endif
       class="@if($navigationItem->isActive()) bg-gray-50 text-hailo-400 font-semibold @else text-gray-600 hover:text-hailo-400 @endif flex gap-x-3 rounded-md p-2 text-sm leading-6">
        @svg($navigationItem->getIcon(), 'h-6 w-6 shrink-0')
        {{ $navigationItem->getLabel() }}
    </a>
</li>
