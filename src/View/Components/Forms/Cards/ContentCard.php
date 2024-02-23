<?php

namespace Pardalsalcap\Hailo\View\Components\Forms\Cards;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Pardalsalcap\Hailo\Forms\Fields\FormField;
use Pardalsalcap\Hailo\Forms\Form;
use Pardalsalcap\Hailo\Models\Content;
use Pardalsalcap\Hailo\Models\Media;
use Pardalsalcap\Hailo\Tables\Columns\MediaColumn;
use Pardalsalcap\Hailo\Tables\Columns\TextColumn;

class ContentCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public Form $form, public FormField $input, public Content|int $content, public string $mode)
    {
        if (is_int($this->content)) {
            $this->content = Content::find($this->content);
        }

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string|null
    {
        return view('hailo::components.forms.cards.content-card', ['content' => $this->content]);
    }
}
