<td class="{{ $column->getCss() }} whitespace-nowrap border-b border-gray-200 py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 lg:pl-8">
    @if ($column->getHasUrl())
        <a @if($column->getOpenInNewTab()) target="_blank" @endif href="{{ $column->getUrl($model) }}"
           class="text-gray-700 hover:text-indigo-900">
            {!!  $column->getDisplay($model)  !!}
        </a>
    @else
        @if ($model->is_image)
            <img src="{{ $model->getUrl()  }}"  class="h-32 w-35 rounded-lg">
        @else
            {!!  $column->getDisplay($model)  !!}
        @endif
    @endif
</td>
