<?php

namespace Pardalsalcap\Hailo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $content_id
 * @property string $key
 * @property string $value
 * @property Content $content
 */
class ContentMeta extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array<string>
     */
    protected $fillable = ['content_id', 'key', 'value'];

    public function content(): BelongsTo
    {
        return $this->belongsTo('App\Models\Content');
    }
}
