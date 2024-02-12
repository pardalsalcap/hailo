<div>
    @if($show)
        <div class="fixed w-full h-screen top-0 left-0 z-40 bg-white"></div>
        <div class="absolute w-full top-0 left-0 z-50 bg-white" style="max-height: 500px;"
             x-data="{
                croppr: new Croppr('#cropping_image', {
                    @if ($mode=="exact")
                        aspectRatio: {{ $width }}/ {{ $height }},
                        minSize: [{{ $width }}, {{ $height }}]
                    @elseif ($mode=="proportional")
                        aspectRatio: null
                        {{--}},
                        maxSize: [{{ $width }}, {{ $height }}]--}}
                    @endif
                }),
                trigger: {
                    ['@click']() {
                    console.log(this.croppr.getValue());
                    $dispatch('cropDone', { data: this.croppr.getValue()});

                    }
                }
             }"

             x-init="function() {}
        "
        >
            <div class="h-8">
                <button x-bind="trigger"
                        class="float-right cursor-pointer text-green-500 hover:text-green-700">{{ __("hailo::medias.save") }}</button>
                <a wire:click="close"
                   class="float-right cursor-pointer text-red-500 hover:text-red-700">{{ __("hailo::medias.close") }}</a>
            </div>
            <div class="grid grid-cols-2">
                <div class="p-4 cropper">
                    <img class="hidden" id="cropping_image" src="{{ $image }}">
                </div>
                <x-hailo::sections.form-container id="media_uploader" :title="'Sustituir la imagen'">
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
                    maxFiles: 1,
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
                            formData.append('media_id', '{{ $media_id }}');
                            formData.append('version', '{{ $version }}');
                            formData.append('action', 'replace');
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
                                Livewire.dispatch('addedMediaVersion', {media_ids: dropzone_ids});
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
            </div>
        </div>

    @endif

</div>
