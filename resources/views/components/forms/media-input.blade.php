<div class="mb-4">
    <x-hailo::forms.label :input="$input"/>
    <div class="mt-2">
        <input
            wire:model="formData.{{ $form->getName() }}.{{ $input->getName() }}"
            id="{{ $input->getName() }}"
            name="{{ $input->getName() }}"
            type="text"
            value="{{ $input->getValue() }}"
            autocomplete="{{ $input->getName() }}"
            @if (in_array('required', $input->getRules($form))) required @endif
            @if (!empty($input->getPlaceholder())) placeholder="{{ $input->getPlaceholder() }}" @endif
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
    </div>

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
                                                this.on('error', function (file, response) {
                                                    error_files = error_files + '<br/>' + file.name;
                                                    if (response.data.error_messages_str !== undefined) {
                                                        error_files = error_files + '<br/>' + response.data.error_messages_str;
                                                    }
                                                });
                                                this.on('sending', function(file, xhr, formData) {
                                                    //formData.append('task_id', task_id );
                                                    formData.append('_token', '{{ csrf_token() }}');
                                                });
                                                this.on('success', function (file, response) {
                                                    if (response.err === true) {
                                                        error_files = error_files + '<br/>' + file.name;
                                                    } else {
                                                        dropzone_ids.push(response.data.media.id);
                                                    }
                                                });
                                                this.on('complete', function (file) {
                                                    this.removeFile(file);
                                                });
                                                this.on('queuecomplete', function (file, response) {
                                                    if (error_files !== '') {
                                                        //launchToast (upload_error_title, 'error')
                                                    } else {
                                                        //launchToast ('upload_success_title', 'success')
                                                        //Livewire.dispatch('uploadComplete', {media_ids: dropzone_ids, task_id: task_id});
                                                        dropzone_ids = [];
                                                    }
                                                });
                                            }
                                        };

                                        new Dropzone($refs.dropzone_comments, dropzone_multi_file_uploader_config);
                                    };
                                ">
        <div x-ref="dropzone_comments" id="file_upload" class="dropzone">
            <span class="block text-gray-400 text-center">Arrastra tus ficheros a esta zona o haz click aquí</span>
        </div>
    </div>

</div>
