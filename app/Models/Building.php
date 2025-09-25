<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 * schema="Building",
 * title="Building",
 * description="A Building object",
 * @OA\Property(
 * property="id",
 * type="integer",
 * readOnly=true,
 * example=1
 * ),
 * @OA\Property(
 * property="address",
 * type="string",
 * example="123 Main St"
 * ),
 * @OA\Property(
 * property="longitude",
 * type="number",
 * format="float",
 * example="-74.0059"
 * ),
 * @OA\Property(
 * property="lattitude",
 * type="number",
 * format="float",
 * example="40.7128"
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
class Building extends Model
{
    protected $fillable = [
        'address',
        'longitude',
        'lattitude'
    ];

    public function organisations(): HasMany {
        return $this->hasMany(Organisation::class);
    }
}
