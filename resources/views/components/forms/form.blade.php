<x-hailo::sections.form-container :id="$form->getName()" :title="$form->getTitle()">
    <form
        @if($form->getLivewire())
            wire:submit="{{ $form->getAction() }}"
        @else
            action="{{ $form->getAction() }}"
            method="{{ $form->getMethod() }}"
        @endif
        >
        @foreach($form->getSchema() as $element)
            <x-hailo::form-element :form="$form" :element="$element"/>
        @endforeach

        {{ $slot }}

        <x-hailo::form-validation :errors="$validation" />

        <div class="my-4 pt-4 border-t border-gray-200">
            <button class="primary" type="submit">{{ $form->getButton() }}</button>
            <button class="secondary" type="button" wire:click="cancel">{{ __("hailo::hailo.cancel") }}</button>
        </div>
    </form>
</x-hailo::sections.form-container>
