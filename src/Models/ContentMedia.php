<?php

namespace Pardalsalcap\Hailo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $content_id
 * @property int $media_id
 * @property string $type
 * @property int $position
 * @property mixed $config
 * @property Content $content
 * @property Media $media
 */
class ContentMedia extends Model
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
    protected $fillable = ['content_id', 'media_id', 'type', 'position', 'config'];

    public function content(): BelongsTo
    {
        return $this->belongsTo('App\Models\Content');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo('App\Models\Media');
    }
}
