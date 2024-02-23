<div class="mb-4">
    <div class="mt-2">
        <x-hailo::forms.label :input="$input"/>
        <div class="border border-gray-100 rounded-md p-4 mt-4">
            <div class="text-gray-500 text-sm flex">
                <div class=" mr-2  flex justify-center items-center">
                    <div class="rounded-full w-8 h-8 bg-gray-300 flex justify-center items-center">
                        <img src="{{ config('hailo.favicon') }}" alt="Anfibic" class="w-5 h-5">
                    </div>
                </div>
                <div>
                    <span class="font-semibold text-base">{{ config('app.name') }}</span><br/>
                    {{ config("app.url") }} › {{ $data['title'] }}
                </div>

            </div>
            <div class="text-indigo-600 text-xl">
                {{ $data['seo_title'] }}
            </div>
            <div class="text-gray-700 text-sm">
                {{ $data['seo_description'] }}
            </div>
        </div>
    </div>
</div>
