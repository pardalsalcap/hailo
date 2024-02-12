<?php

namespace Pardalsalcap\Hailo\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Translatable\HasTranslations;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $visibility
 * @property boolean $status
 * @property mixed $title
 * @property mixed $alt
 * @property boolean $is_image
 * @property string $original
 * @property string $mimetype
 * @property string $disk
 * @property string $directory
 * @property string $filename
 * @property string $extension
 * @property string $url
 * @property integer $weight
 * @property integer $height
 * @property integer $width
 * @property mixed $metadata
 * @property mixed $versions
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 */
class Media extends Model
{
    use HasTranslations;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'medias';
    public $translatable = ['title', 'alt'];
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'visibility', 'status', 'title', 'alt', 'is_image', 'original', 'mimetype', 'disk', 'directory', 'filename', 'extension', 'url', 'weight', 'height', 'width', 'metadata', 'versions', 'created_at', 'updated_at'];

    protected $casts=[
        'metadata' => 'array',
        'versions' => 'array',
        'title' => 'array',
        'alt' => 'array',
    ];

    public function getUrl ($version = null)
    {
        if ($version and $this->is_image and $this->versions and array_key_exists($version, $this->versions)) {
            $path = $this->versions[$version];
            return Storage::disk($this->disk)->url($path['path']);
        }

        if ($this->is_image and isset($this->versions['webp']) and !empty($this->versions['webp'])) {
            return Storage::disk($this->disk)->url($this->versions['webp']['url']);
        }

        return  Storage::disk($this->disk)->url($this->url);
    }

    public function weightToHuman()
    {
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

        for ($i = 0; $this->weight > 1024; $i++) {
            $this->weight /= 1024;
        }

        return round( $this->weight, 2) . ' ' . $units[$i];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}
