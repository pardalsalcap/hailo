<div class="table-actions">
    @if ($table->getHasEditAction())
        <a wire:click="edit({{ $model->id }})" href="javascript:void(0)" class="table-action edit">
            @svg('edit', 'h-4 w-4')
        </a>
    @endif
    @if ($table->getHasDeleteAction())
        <a wire:click="confirmDelete({{ $model->id }})" href="javascript:void(0)" class="table-action delete">
            @svg('delete', 'h-4 w-4')
        </a>
    @endif
</div>
