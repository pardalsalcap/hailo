<?php

namespace Pardalsalcap\Hailo\View\Components\Cards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Pardalsalcap\Hailo\Models\Media;
use Pardalsalcap\Hailo\Tables\Columns\MediaColumn;
use Pardalsalcap\Hailo\Tables\Columns\TextColumn;

class MediaCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public \Pardalsalcap\Hailo\Tables\Table $table, public Media $media)
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string|null
    {

        return view('hailo::components.tables.cards.media-card', ['media' => $this->media, 'table' => $this->table]);
    }
}
