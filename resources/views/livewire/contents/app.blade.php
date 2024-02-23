<div>
    @if ($action=='index')
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-3">
                <x-hailo::table :table="$contents_table"/>
            </div>
            <div id="table-sidebar">
                <section id="create-content-box" class="form-container">
                    <h1>&nbsp;</h1>
                    <div class="bg-white p-8 rounded-b-xl border-t-2 border-hailo-400">
                        <div class="relative block text-left" x-data="{open:false}">
                            <div>
                                <button @click="open=!open" type="button" class="primary w-full" id="menu-button"
                                        aria-expanded="true" aria-haspopup="true">
                                    {{ __("hailo::content.create_btn") }}
                                    <svg class="-mr-1 h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>

                            <div x-show="open===true"
                                 class="absolute right-0 z-10 mt-2 w-full origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                 role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                <div class="py-1" role="none">
                                    @foreach (config('hailo.content_types') as $type)
                                        <a href="{{ route('hailo.contents', ['action'=>"create", "type"=>$type]) }}"
                                           class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100"
                                           role="menuitem" tabindex="-1"
                                           id="menu-item-{{ $loop->index }}">{{ __("hailo::content_types.".$type) }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                @if ($contents_table->hasFilters() and $contents_table->getFiltersLayout()=='sidebar')
                    <section id="create-content-box" class="form-container mt-8">
                        <h1>{{ __("hailo::content.filters_section_title") }}</h1>
                        <div class="bg-white p-8 rounded-b-xl border-t-2 border-hailo-400">
                            <nav class="space-y-2" aria-label="Tabs">
                                <button wire:click="filterBy('all')" href="#"
                                    @class([
                                        'block w-full text-left whitespace-nowrap border-b py-4 px-1 text-sm font-medium border-gray-300',
                                        'text-gray-500 hover:border-hailo-600 hover:text-gray-700'=> ($contents_table->getCurrentFilter() != 'all' and $contents_table->getCurrentFilters() != ['all']),
                                        'border-hailo-600 text-hailo-600' => $contents_table->getCurrentFilter() === 'all' and $contents_table->getCurrentFilters() === ['all'],
                                    ])
                                >
                                    {{ __('hailo::hailo.all') }}
                                </button>
                                <!-- Current: "border-indigo-500 text-indigo-600", Default: "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" -->
                                @foreach ($contents_table->getFilters() as $filter)
                                    @if($filter['at_filters'] === true)
                                        <button wire:click="filterBy('{{ $filter['name'] }}')" href="#"
                                            @class([
                                            'block w-full text-left whitespace-nowrap border-b py-4 px-1 text-sm font-medium border-gray-300',
                                            'text-gray-500 hover:border-hailo-600 hover:text-gray-700'=> $contents_table->getCurrentFilter() != $filter['name'] and !in_array($filter['name'], $contents_table->getCurrentFilters()),
                                            'border-hailo-600 text-hailo-600' => $contents_table->getCurrentFilter() === $filter['name'] or in_array($filter['name'], $contents_table->getCurrentFilters()),
                                       ])>
                                            {{ $filter['label'] }}
                                        </button>
                                    @endif
                                @endforeach
                            </nav>

                        </div>

                    </section>
                @endif
            </div>
        </div>
    @elseif ($action=='edit')
        <x-hailo::form :validation="$validation_errors[$contents_form->getName()]??null"
                       :data="$formData['content_form']"
                       :form="$contents_form"/>
        <livewire:select-media-app/>
        <livewire:select-content-app/>

    @elseif ($action=='create')
        <x-hailo::form :validation="$validation_errors[$contents_form->getName()]??null"
                       :data="$formData['content_form']"
                       :form="$contents_form"/>
        <livewire:select-media-app/>
        <livewire:select-content-app/>
    @endif

    @if ($action=='edit' or $action=='create')
        @push('scripts')
            <script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.16.1/ckeditor.js"></script>
        @endpush
    @endif

</div>
