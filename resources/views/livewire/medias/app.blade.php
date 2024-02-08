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
            <x-hailo::form :validation="$validation_errors[$medias_form->getName()]??null" :form="$medias_form"/>
        @else
            <x-hailo::sections.form-container id="media_uploader" :title="$medias_form->getTitle()">
                <div wire:ignore x-data x-init="function() {
                Dropzone.autoDiscover = false;

                let error_files = '';
                let dropzone_ids = [];
                let dropzone_multi_file_uploader_config = {
                    url: '{{ route("hailo.upload") }}',
                    paramName: 'file',
                    maxFilesize: 100,
                    parallelUploads: 1,
                    dictDefaultMessage: '',
                    maxFiles: 10,
                    accept: function (file, done) {
                        done();
                    },
                    init: function () {
                        this.on('addedfile', function (file) {
                            if(file.size > (1024 * 1024 * 5))
                            {
                                error_files = error_files + '<br/>'+file.name+'{{ __("hailo::medias.file_is_too_big", ["s"=>"10MB"])}}';
                                this.removeFile(file);
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
