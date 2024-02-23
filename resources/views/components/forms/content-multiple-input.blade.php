<div class="mb-4" wire:key="group-{{ $input->getName() }}">
    <input type="hidden"
           wire:model="formData.{{ $form->getName() }}.{{ $input->getName() }}"
           id="{{ $input->getName() }}"
           name="{{ $input->getName() }}"
           value="{{ $data[$input->getName()]? implode(",", $data[$input->getName()]) :'' }}"/>
<div wire:sortable-group.item-group="{{ $input->getName() }}" wire:sortable-group.options="{ animation: 100 }">
        @if (!empty($data[$input->getName()]))
            @foreach ($data[$input->getName()] as $content)

                <div wire:sortable-group.item="{{  $content }}" wire:key="content-{{  $content }}"  class="mb-4">
                    <x-hailo::content-form-card :form="$form" :input="$input" :content="$content" mode="multiple"/>
                </div>
            @endforeach
        @else
            <p class="py-4 text-center">No hay contenido relacionado todavía</p>
        @endif

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
        wire:click="$dispatch('selectContent', {type: '{{ $input->getType() }}', input: '{{ $input->getName() }}', form: '{{ $form->getName() }}', mode: 'multiple', current: [{{ implode(",", $data[$input->getName()]) }}] });"
        @click="scrollTo({top: 0, behavior: 'smooth'})">
        Selecciona el contenido
    </button>

</div>
