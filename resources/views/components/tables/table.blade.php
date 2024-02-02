<x-hailo::sections.table-container :id="$table->getName()" :title="$table->getTitle()">
    @if ($table->hasFilters())
        <div>
            <div class="sm:hidden">
                <label for="tabs" class="sr-only">Select a tab</label>
                <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                <select id="tabs" name="tabs"
                        class="block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                    <option>My Account</option>
                    <option>Company</option>
                    <option selected>Team Members</option>
                    <option>Billing</option>
                </select>
            </div>
            <div class="hidden sm:block mx-8 mb-8">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button wire:click="filterBy('all')" href="#"
                            @class([
                                'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium',
                                'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'=> $table->getCurrentFilter() != 'all',
                                'border-hailo-600 text-hailo-600' => $table->getCurrentFilter() === 'all',
                            ])
                        >
                            {{ __('hailo::hailo.all') }}
                        </button>
                        <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" -->
                        @foreach ($table->getFilters() as $filter)
                            <button wire:click="filterBy('{{ $filter['name'] }}')" href="#"
                                @class([
                                'whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium',
                                'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'=> $table->getCurrentFilter() != $filter['name'],
                                'border-hailo-600 text-hailo-600' => $table->getCurrentFilter() === $filter['name'],
                           ])>
                                {{ $filter['label'] }}
                            </button>
                        @endforeach
                    </nav>
                </div>
            </div>
        </div>
    @endif
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
            <th scope="col"
                class="sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-3 pr-4 backdrop-blur backdrop-filter sm:pr-6 lg:pr-8">
            </th>
        </tr>
        </thead>
        <tbody>
        @foreach($table->getData() as $item)
            <tr>
                @foreach($table->getSchema() as $column)
                    <x-hailo::table-element :table="$table" :element="$column" :model="$item"/>
                @endforeach
                <td class="relative whitespace-nowrap border-b border-gray-200 py-4 pr-4 pl-3 text-right text-sm font-medium sm:pr-8 lg:pr-8">
                    <x-hailo::row-actions :table="$table" :element="$column" :model="$item"/>
                </td>
            </tr>
        @endforeach
        @if (count($table->getData()) == 0)
            <tr>
                <td colspan="{{ count($table->extractHeadings ()) + 1 }}"
                    class="whitespace-nowrap border-b border-gray-200 text-hailo-600 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
                    {{ $table->getNoRecordsFound() }}
                </td>
            </tr>
        @endif
        </tbody>
    </table>
    <div class="mt-4 mx-8">
        {{ $table->getData()->appends($table->getPaginationAppends())->links('hailo::pagination') }}
    </div>
</x-hailo::sections.table-container>
