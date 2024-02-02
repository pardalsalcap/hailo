<?php

namespace Pardalsalcap\Hailo\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $key
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 */
class UserPreference extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'key', 'value', 'created_at', 'updated_at'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}
