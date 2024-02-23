<?php

namespace Pardalsalcap\Hailo\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Pardalsalcap\Hailo\Models\Content;
use Pardalsalcap\Hailo\Models\Media;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $content_id
 * @property integer $media_id
 * @property string $type
 * @property integer $position
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

    /**
     * @return BelongsTo
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo('App\Models\Content');
    }

    /**
     * @return BelongsTo
     */
    public function media(): BelongsTo
    {
        return $this->belongsTo('App\Models\Media');
    }
}
