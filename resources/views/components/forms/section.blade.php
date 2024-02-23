<section id="{{ $section->getName() }}" class="form-section">
    @if(!empty($section->getTitle()))
        <h2>{{ $section->getTitle() }}</h2>
    @endif

    <div class="grid grid-cols-{{ $section->getColumns() }} gap-y-4 gap-x-16">
        @foreach ($section->getSchema() as $sectionElement)
            @if ($sectionElement instanceof \Pardalsalcap\Hailo\Forms\Section)
                <div class="col-span-{{ $sectionElement->getColSpan() }}">
            @endif
                <x-hailo::form-element :data="$data" :form="$form" :element="$sectionElement"/>
            @if ($sectionElement instanceof \Pardalsalcap\Hailo\Forms\Section)
                </div>
            @endif
        @endforeach
    </div>
</section>
