@if (!is_null($errors) and $errors->any())
    <div wire:loading.remove class="rounded-md bg-red-50 p-4 mt-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-red-600" width="24" height="24"
                     viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/>
                    <path d="M12 17l0 .01"/>
                    <path d="M12 13.5a1.5 1.5 0 0 1 1 -1.5a2.6 2.6 0 1 0 -3 -4"/>
                </svg>
            </div>
            <div class="ml-3">
                <div class="mt-2 text-sm text-red-600">
                    <ul role="list" class="space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{!!  $error !!}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
