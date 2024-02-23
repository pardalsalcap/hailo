<div class="">
    @if($show)
        <div class="relative z-40" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!--
              Background backdrop, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0"
                To: "opacity-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100"
                To: "opacity-0"
            -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <!--
                      Modal panel, show/hide based on modal state.

                      Entering: "ease-out duration-300"
                        From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        To: "opacity-100 translate-y-0 sm:scale-100"
                      Leaving: "ease-in duration-200"
                        From: "opacity-100 translate-y-0 sm:scale-100"
                        To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    -->
                    <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 w-full mx-16 sm:p-6">

                        <x-hailo::sections.table-container :id="$medias_table->getName()" :title="$medias_table->getTitle()">
                            <div class="grid grid-cols-8 gap-8 px-8">
                                @foreach($medias_table->getData() as $item)
                                    <x-hailo::media-select-card :selected="in_array($item->id, $selected)" :table="$medias_table" :media="$item"/>
                                @endforeach
                            </div>
                            <div class="mt-8 mx-8">
                                {{ $medias_table->getData()->appends($medias_table->getPaginationAppends())->links('hailo::pagination') }}
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
