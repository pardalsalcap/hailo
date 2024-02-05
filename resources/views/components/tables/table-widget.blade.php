<x-hailo::sections.table-container :id="$table->getName()" :title="$table->getTitle()">
    <table class="min-w-full border-separate border-spacing-0">
        <thead>
        <tr>
            @foreach ($table->extractHeadings () as $key=>$heading)
                <th scope="col"
                    class="{{ $table->getColumnCss($key) }} sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8">
                    {{ $heading['label'] }}
                    @if($heading['sortable'])
                        <button
                            wire:click="sort('{{ $heading['name'] }}', '{{ $table->resolveSortDirection($heading['name']) }}')"
                            class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            @svg($table->resolveSortIcon($heading['name']), 'inline-block w-4 h-4 ml-1 '.$table->resolveSortCss($heading['name']))
                        </button>
                    @endif
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @foreach($table->getData() as $item)
            <tr>
                @foreach($table->getSchema() as $column)
                    <x-hailo::table-element :table="$table" :element="$column" :model="$item"/>
                @endforeach
            </tr>
        @endforeach
        @if (count($table->getData()) == 0)
            <tr>
                <td colspan="{{ count($table->extractHeadings ())  }}"
                    class="whitespace-nowrap border-b border-gray-200 text-hailo-600 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
                    {{ $table->getNoRecordsFound() }}
                </td>
            </tr>
        @endif
        </tbody>
    </table>
    <div class="mt-8 mx-8">
        <a class="primary" wire:navigate href="{{ $widget->getRoute() }}">
            {{ __("hailo::hailo.see_all") }}
        </a>
    </div>
</x-hailo::sections.table-container>
