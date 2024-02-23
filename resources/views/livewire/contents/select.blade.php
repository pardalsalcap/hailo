<div class="">
    @if($show)
        <div class="relative z-40" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 w-full mx-16 sm:p-6">

                        <x-hailo::sections.table-container :id="$contents_table->getName()" :title="$contents_table->getTitle()">
                            <div class="grid grid-cols-8 gap-8 px-8">
                                @foreach($contents_table->getData() as $item)
                                    <x-hailo::content-select-card :selected="in_array($item->id, $selected)" :table="$contents_table" :content="$item"/>
                                @endforeach
                            </div>
                            <div class="mt-8 mx-8">
                                {{ $contents_table->getData()->appends($contents_table->getPaginationAppends())->links('hailo::pagination') }}
                            </div>
                        </x-hailo::sections.table-container>



                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3 max-w-lg">
                            <button wire:click="end()" type="button" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:col-start-2">
                                {{ __("hailo::hailo.save") }}
                            </button>
                            <button wire:click="close()" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0">
                                {{ __("hailo::hailo.cancel") }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    @endif

</div>
