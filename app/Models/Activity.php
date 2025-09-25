<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @OA\Schema(
 * schema="Activity",
 * title="Activity",
 * description="An Activity object representing a hierarchical organisation activity types.",
 * @OA\Property(
 * property="id",
 * type="integer",
 * readOnly=true,
 * example=1
 * ),
 * @OA\Property(
 * property="name",
 * type="string",
 * example="Food"
 * ),
 * @OA\Property(
 * property="parent_id",
 * type="integer",
 * nullable=true,
 * example=null
 * ),
 * @OA\Property(
 * property="depth",
 * type="integer",
 * example=0
 * ),
 * @OA\Property(
 * property="created_at",
 * type="string",
 * format="date-time",
 * readOnly=true,
 * example="2023-01-01T12:00:00Z"
 * ),
 * @OA\Property(
 * property="updated_at",
 * type="string",
 * format="date-time",
 * readOnly=true,
 * example="2023-01-01T12:00:00Z"
 * )
 * )
 */
class Activity extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
        'depth'
    ];

    public function parent(): BelongsTo {
        return $this->belongsTo(Activity::class, 'parent_id');
    }

    public function children(): HasMany {
        return $this->hasMany(Activity::class, 'parent_id');
    }

    public function organisations(): BelongsToMany {
        return $this->belongsToMany(Organisation::class);
    }
}
