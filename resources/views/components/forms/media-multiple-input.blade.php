<div class="mb-4" wire:key="group-{{ $input->getName() }}">
    <input type="hidden"
           wire:model="formData.{{ $form->getName() }}.{{ $input->getName() }}"
           id="{{ $input->getName() }}"
           name="{{ $input->getName() }}"
           value="{{ $data[$input->getName()]? implode(",", $data[$input->getName()]) :'' }}"/>
<div wire:sortable-group.item-group="{{ $input->getName() }}" wire:sortable-group.options="{ animation: 100 }">
        @if (!empty($data[$input->getName()]))
            @foreach ($data[$input->getName()] as $media)
                <div wire:sortable-group.item="{{  $media }}" wire:key="media-{{  $media }}"  class="mb-4">
                    <x-hailo::media-form-card :form="$form" :input="$input" :media="$media" mode="multiple"/>
                </div>
            @endforeach
        @endif
</div>

    <div class="mt-0" wire:ignore x-data x-init="function() {
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
                            }
                        });
                        this.on('error', function (file, response) {
                            error_files = error_files + '<br/>' + file.name;
                            if (response.message !== undefined) {
                                error_files = error_files + '<br/>' + response.message;
                            }
                        });
                        this.on('sending', function(file, xhr, formData) {
                            formData.append('_token', '{{ csrf_token() }}');
                        });
                        this.on('success', function (file, response) {
                            if (response.success === false) {
                                error_files = error_files + '<br/>' + file.name;
                            } else {
                                dropzone_ids.push(response.media);
                            }
                        });
                        this.on('complete', function (file) {
                            this.removeFile(file);
                        });
                        this.on('queuecomplete', function (file, response) {
                            if (error_files !== '') {
                                Livewire.dispatch('toast-error', [{title: error_files}]);
                            } else {
                                Livewire.dispatch('addedMedia', {media_ids: dropzone_ids, input: '{{ $input->getName() }}', form: '{{ $form->getName() }}', mode: 'multiple'});
                                dropzone_ids = [];
                            }
                            error_files = '';
                        });
                    }
                };

                new Dropzone($refs.dropzone_comments, dropzone_multi_file_uploader_config);
            };
        ">
        <div x-ref="dropzone_comments" id="file_upload" class="dropzone dropzone_form">
                    <span
                        class="block text-gray-400 text-center">{{ __("hailo::hailo.dropzone_message") }}</span>
        </div>

    </div>


    <button
        type="button"
        class="rounded-b-md bg-hailo-400
    px-3.5 py-2.5
    text-sm font-semibold text-white
    shadow-sm
    hover:bg-hailo-600
    focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600
    uppercase
        font-bold block w-full"
        wire:click="$dispatch('selectMedia', {type: '{{ $input->getType() }}', input: '{{ $input->getName() }}', form: '{{ $form->getName() }}', mode: 'multiple', current: [{{ implode(",", $data[$input->getName()]) }}] });"
        @click="scrollTo({top: 0, behavior: 'smooth'})">
        @if($input->getType() == 'image')
            O Selecciona una imagen
        @else
            O selecciona un fichero
        @endif
    </button>

</div>
