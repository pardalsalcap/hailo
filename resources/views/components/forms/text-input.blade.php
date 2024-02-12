<div class="mb-4">


    @if ($input->isTranslatable())
        <h2>
            {{ $input->getLabel() }}
        </h2>
        @if ($input->getHelp())
            <p class="mt-2 text-sm text-gray-500">{{ $input->getHelp() }}</p>
        @endif
        @foreach(config('hailo.languages') as $iso=>$language)
            <div class="mt-2">
                <label for="{{ $input->getName() }}_{{ $iso }}" class="block text-sm font-medium leading-6 text-gray-400">
                    {{ $input->getLabel() }} ({{ $language }})
                </label>
                <input
                    wire:model="formData.{{ $form->getName() }}.{{ $input->getName() }}_{{ $iso }}"
                    id="{{ $input->getName() }}_{{ $iso }}"
                    name="{{ $input->getName() }}"
                    type="{{ $input->getType() }}"
                    autocomplete="{{ $input->getName() }}"
                    @if (in_array('required', $input->getRules($form))) requiredo @endif
                    @if (!empty($input->getPlaceholder())) placeholder="{{ $input->getPlaceholder() }}" @endif
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
            </div>
        @endforeach
    @else
        <div class="mt-2">
            <x-hailo::forms.label :input="$input"/>
            <input
                wire:model="formData.{{ $form->getName() }}.{{ $input->getName() }}"
                id="{{ $input->getName() }}"
                name="{{ $input->getName() }}"
                type="{{ $input->getType() }}"
                value="{{ $input->getValue() }}"
                autocomplete="{{ $input->getName() }}"
                @if (in_array('required', $input->getRules($form))) requiredo @endif
                @if (!empty($input->getPlaceholder())) placeholder="{{ $input->getPlaceholder() }}" @endif
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
        </div>
    @endif

</div>
