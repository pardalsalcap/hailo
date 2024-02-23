<div class="mb-4">
    <div class="mt-2"  wire:ignore>
        <x-hailo::forms.label :input="$input"/>
        <textarea
            wire:model="formData.{{ $form->getName() }}.{{ $input->getName() }}"
            x-data
            x-init="
              CKEDITOR.replace('{{ $input->getName() }}', {
                customConfig: '/vendor/hailo/js/ckeditor_config.js'
              });
              CKEDITOR.instances.{{ $input->getName() }}.on('change', function() {
                $dispatch('input', this.getData());
              });"
            wire:key="ckEditor_{{ $input->getName() }}"
            x-ref="ckEditor_{{ $input->getName() }}"
            wire:model.debounce.9999999ms="formData.{{ $form->getName() }}.{{ $input->getName() }}"
            id="{{ $input->getName() }}"
            name="{{ $input->getName() }}"
            @if (in_array('required', $input->getRules($form))) required @endif
            @if (!empty($input->getPlaceholder())) placeholder="{{ $input->getPlaceholder() }}" @endif
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ $input->getValue() }}</textarea>
    </div>
</div>
