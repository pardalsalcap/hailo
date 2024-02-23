<div class="mb-4">
    <div class="flex items-center">
        <button type="button"
                wire:click="toggle('{{ $input->getName() }}', '{{ $form->getName() }}')"
                @class(
                    [
                        'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2',
                        'bg-lime-400' => $data[$input->getName()] == true,
                        'bg-hailo-400' => $data[$input->getName()]==false,
                    ]
                )
                role="switch" aria-checked="false" aria-labelledby="annual-billing-label">
            <span aria-hidden="true"
                  @class([
                    'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                    'translate-x-5' => $data[$input->getName()] == true,
                    'translate-x-0' => $data[$input->getName()]==false,
                ])></span>
        </button>
        <span class="ml-3 text-sm" id="annual-billing-label">
    <span class="font-medium text-gray-900">{{ $input->getLabel() }}</span>
  </span>
    </div>


</div>
