<section id="{{ $section->getName() }}" class="form-section">
    @if(!empty($section->getTitle()))
        <h2>{{ $section->getTitle() }}</h2>
    @endif
    <div class="grid grid-cols-{{ $section->getColumns() }} gap-4">
        @foreach ($section->getSchema() as $sectionElement)
            <x-hailo::form-element :form="$form" :element="$sectionElement"/>
        @endforeach
    </div>
</section>
