<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @OA\Schema(
 * schema="Organisation",
 * title="Organisation",
 * description="An Organisation object representing a company or group.",
 * @OA\Property(
 * property="id",
 * type="integer",
 * readOnly=true,
 * example=101
 * ),
 * @OA\Property(
 * property="name",
 * type="string",
 * example="Innovate Inc."
 * ),
 * @OA\Property(
 * property="phone",
 * type="string",
 * example="555-123-4567"
 * ),
 * @OA\Property(
 * property="building_id",
 * type="integer",
 * example=1
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
 * * @OA\Schema(
 * schema="FullOrganisation",
 * title="Full Organisation with relations",
 * description="An Organisation object including its related building and activities.",
 * allOf={
 * @OA\Schema(ref="#/components/schemas/Organisation"),
 * @OA\Schema(
 * @OA\Property(
 * property="building",
 * ref="#/components/schemas/Building"
 * ),
 * @OA\Property(
 * property="activities",
 * type="array",
 * @OA\Items(
 * allOf={
 * @OA\Schema(ref="#/components/schemas/Activity"),
 * @OA\Schema(
 * @OA\Property(
 * property="pivot",
 * type="object",
 * @OA\Property(property="organisation_id", type="integer", example=1),
 * @OA\Property(property="activity_id", type="integer", example=1)
 * )
 * )
 * }
 * )
 * )
 * )
 * }
 * )
 */

class Organisation extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'building_id'
    ];

    public function building(): BelongsTo {
        return $this->belongsTo(Building::class);
    }

    public function activities(): BelongsToMany {
        return $this->belongsToMany(Activity::class);
    }
}
