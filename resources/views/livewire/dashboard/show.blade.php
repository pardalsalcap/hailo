<div wire:key="rand()">
    @foreach ($widgets->widgets()->getWidgets() as $widget)
        <div class="grid grid-cols-{{ $widgets->widgets()->getColumns() }}">
            @if ($widget->getType()=='table')
                <x-hailo::table-widget :widget="$widget"/>
            @endif
        </div>
    @endforeach
</div>
