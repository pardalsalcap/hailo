<?php

namespace Pardalsalcap\Hailo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Pardalsalcap\Hailo\Traits\ContentTrait;

/**
 * @property integer $id
 * @property integer $parent_id
 * @property integer $user_id
 * @property integer $translation_id
 * @property integer $featured_image_id
 * @property integer $featured_download_id
 * @property string $type
 * @property string $template
 * @property integer $position
 * @property boolean $status
 * @property string $lang
 * @property string $title
 * @property string $claim
 * @property string $quoted
 * @property string $summary
 * @property string $content
 * @property string $widgets
 * @property mixed $configuration
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property string $seo_url
 * @property string $seo_slug
 * @property string $seo_breadcrumb
 * @property string $created_at
 * @property string $updated_at
 * @property ContentMeta[] $contentMetas
 * @property Media $featuredDownload
 * @property Media $featuredImage
 * @property Content $parent
 * @property Content $translations
 * @property User $user
 */
class Content extends Model
{
    use ContentTrait;

    protected $casts = [
        'configuration' => 'array',
    ];

    /**
     * @var array<string>
     */
    protected $fillable = ['parent_id',
        'user_id',
        'translation_id',
        'featured_image_id',
        'featured_download_id',
        'type',
        'template',
        'position',
        'status',
        'lang',
        'title',
        'claim',
        'quoted',
        'summary',
        'content',
        'widgets',
        'configuration',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'seo_url',
        'seo_slug',
        'seo_breadcrumb',
        'created_at',
        'updated_at'];

    /**
     * @return HasMany
     */
    public function contentMetas(): HasMany
    {
        return $this->hasMany(ContentMeta::class);
    }

    /**
     * @return BelongsTo
     */
    public function featuredDownload(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_download_id');
    }

    /**
     * @return BelongsTo
     */
    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'parent_id');
    }

    /**
     * @return BelongsTo
     */
    public function translation(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'translation_id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }

    // En el modelo Content
    public function imageGallery()
    {
        return $this->belongsToMany(Media::class, 'content_media')
            ->withPivot('position', 'type')
            ->wherePivot('type', '=', 'image_gallery') // Filtrar solo por tipo 'image_gallery'
            ->orderBy('pivot_position'); // Ordenar por el campo 'position'
    }

    public function dwnGallery()
    {
        return $this->belongsToMany(Media::class, 'content_media')
            ->withPivot('position', 'type')
            ->wherePivot('type', '=', 'download_gallery') // Filtrar solo por tipo 'image_gallery'
            ->orderBy('pivot_position'); // Ordenar por el campo 'position'
    }

    public function related()
    {
        return $this->belongsToMany(Content::class, 'content_content', 'content_id', 'related_id')
            ->withPivot('position', 'type')
            ->wherePivot('type', '=', 'related') // Filtrar solo por tipo 'image_gallery'
            ->orderBy('pivot_position'); // Ordenar por el campo 'position'
    }

}
