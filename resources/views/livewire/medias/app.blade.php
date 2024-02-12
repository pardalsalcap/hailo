<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <div @class([
   "lg:col-span-3"=>($action=='index'),
    "lg:col-span-2 opacity-20"=>($action=='edit'),
])>
        <x-hailo::sections.table-container :id="$medias_table->getName()" :title="$medias_table->getTitle()">
            <div class="grid grid-cols-4 gap-8 px-8">
                @foreach($medias_table->getData() as $item)
                    <x-hailo::media-card :table="$medias_table" :media="$item"/>
                @endforeach
            </div>
            <div class="mt-8 mx-8">
                {{ $medias_table->getData()->appends($medias_table->getPaginationAppends())->links('hailo::pagination') }}
            </div>
        </x-hailo::sections.table-container>
    </div>
    <div @class([
   ""=>($action=='index'),
    "lg:col-span-2"=>($action=='edit'),
])>
        @if ($action=='edit')
            <x-hailo::form :validation="$validation_errors[$medias_form->getName()]??null" :form="$medias_form">
                @if ($medias_form->getModel()->is_image)
                    <h2 class="mt-8">{{ __("hailo::medias.crops_title") }}</h2>
                    <p class="mt-2 text-sm text-gray-500">{{ __("hailo::medias.crops_text") }}</p>

                    <div class="mt-8">
                        <h3>
                            {{ __("hailo::medias.version") }} original
                        </h3>
                        <p class="mb-2 text-sm text-gray-500">
                            Esta es la imagen original que has cargado en el sistema. Esta imagen no se utilizará en ningún caso, es solo para que puedas ver la imagen original que has subido. Y gestionar las versiones que necesites.
                        </p>
                            <img src="{{ $medias_form->getModel()->getUrl() }}?t={{ time() }}" class="w-1/2 h-auto"/>
                    </div>

                    @foreach ($medias_form->getModel()->versions as $key=>$version)
                        <div class="mt-8">
                            <h3>
                                {{ __("hailo::medias.version") }} {{ $key }}
                            </h3>
                            <p class="mb-2 text-sm text-gray-500">
                                {{  $version['curator']::info() }}<br />
                                {{ __("hailo::medias.version_text") }}
                            </p>
                            <a wire:click="crop({{ $medias_form->getModel()->id }}, '{{ $key }}')"  @click="scrollTo({top: 0, behavior: 'smooth'})"
                               class="cursor-pointer text-blue-500 hover:text-blue-700">
                                {{ __("hailo::medias.crop") }}
                                <img src="{{ $medias_form->getModel()->getUrl($key) }}?t={{ time() }}"
                                     class="w-1/2 h-auto"/>
                            </a>
                        </div>
                    @endforeach
                @endif
            </x-hailo::form>
            <livewire:crop-app/>
        @else
            <x-hailo::sections.form-container id="media_uploader" :title="$medias_form->getTitle()">
                <div wire:ignore x-data x-init="function() {
                Dropzone.autoDiscover = false;

                let error_files = '';
                let dropzone_ids = [];
                let dropzone_multi_file_uploader_config = {
                    url: '{{ route("hailo.upload") }}',
                    paramName: 'file',
                    maxFilesize: 10,
                    parallelUploads: 1,
                    dictDefaultMessage: '',
                    maxFiles: 10,
                    accept: function (file, done) {
                        done();
                    },
                    init: function () {
                        this.on('addedfile', function (file) {
                            if(file.size > (1024 * 1024 * 10))
                            {
                                error_files = error_files + '<br/>'+file.name+'{{ __("hailo::medias.file_is_too_big", ["s"=>"10MB"])}} ('+humanFileSize(file.size)+')';
                                //this.removeFile(file);
                            }
                        });
                        this.on('error', function (file, response) {
                            error_files = error_files + '<br/>' + file.name;
                            if (response.message !== undefined) {
                                error_files = error_files + '<br/>' + response.message;
                            }
                        });
                        this.on('sending', function(file, xhr, formData) {
                            console.log('sending');
                            formData.append('_token', '{{ csrf_token() }}');
                        });
                        this.on('success', function (file, response) {
                            console.log('success', response);
                            if (response.success === false) {
                                error_files = error_files + '<br/>' + file.name;
                            } else {
                                dropzone_ids.push(response.media);
                            }
                        });
                        this.on('complete', function (file) {
                            console.log('completed', file);
                            this.removeFile(file);
                        });
                        this.on('queuecomplete', function (file, response) {
                            console.log('queuecomplete');
                            if (error_files !== '') {
                                Livewire.dispatch('toast-error', [{title: error_files}]);
                                console.log('error_files', error_files);
                            } else {
                                Livewire.dispatch('addedMedia', {media_ids: dropzone_ids});
                                console.log('dropzone_ids', dropzone_ids);
                                dropzone_ids = [];
                            }
                            error_files = '';
                        });
                    }
                };

                new Dropzone($refs.dropzone_comments, dropzone_multi_file_uploader_config);
            };
        ">
                    <div x-ref="dropzone_comments" id="file_upload" class="dropzone">
                    <span
                        class="block text-gray-400 text-center">Arrastra tus ficheros a esta zona o haz click aquí</span>
                    </div>
                </div>
            </x-hailo::sections.form-container>

        @endif
    </div>
</div>
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.css"
          integrity="sha512-bs9fAcCAeaDfA4A+NiShWR886eClUcBtqhipoY5DM60Y1V3BbVQlabthUBal5bq8Z8nnxxiyb1wfGX2n76N1Mw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
@endpush
