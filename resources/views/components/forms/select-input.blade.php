<div>
    <x-hailo::forms.label :input="$input" />
    <div
        class="mt-2"
        wire:ignore
        >

        <select
            wire:model="formData.{{ $form->getName() }}.{{ $input->getName() }}"
            x-ref="{{ $input->getName() }}"
            id="{{ $input->getName() }}"
            name="{{ $input->getName() }}"
            @if ($input->getMultiple()) multiple @endif
            @if (in_array('required', $input->getRules($form))) required @endif
            @if (!empty($input->getPlaceholder())) placeholder="{{ $input->getPlaceholder() }}" @endif
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            @if ($input->getNullOption())
                <option value="">{{ __("hailo::hailo.select_one_option") }}</option>
            @endif
            @foreach ($input->getOptions() as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>
