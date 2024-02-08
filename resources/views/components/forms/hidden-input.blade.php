<div class="hidden mb-4">
    <x-hailo::forms.label :input="$input" />
    <div class="mt-2">
        <input
            wire:model="formData.{{ $form->getName() }}.{{ $input->getName() }}"
            id="{{ $input->getName() }}"
            name="{{ $input->getName() }}"
            type="{{ $input->getType() }}"
            value="{{ $input->getValue() }}"
            autocomplete="{{ $input->getName() }}"
            @if (in_array('required', $input->getRules($form))) required @endif
            @if (!empty($input->getPlaceholder())) placeholder="{{ $input->getPlaceholder() }}" @endif
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
    </div>
</div>
