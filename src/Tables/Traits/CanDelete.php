<?php

namespace Pardalsalcap\Hailo\Tables\Traits;

trait CanDelete
{
    public ?int $deleting_id = null;

    public array $deleting_configuration = [];

    public function confirmDelete($id)
    {
        $this->deleting_id = $id;
        if (! empty($this->deleting_configuration)) {
            $this->deleting_configuration['id'] = $this->deleting_id;
            $this->dispatch('confirm-modal-destroy', $this->deleting_configuration);
        }
    }
}
