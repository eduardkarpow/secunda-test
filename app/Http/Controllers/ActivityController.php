<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateActivityRequest;
use App\Models\Activity;
use Illuminate\Http\Request;

/**
 *
 * @OA\PathItem(path="/api/activities")
 */
class ActivityController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/activities/{activity}/organisations",
     * operationId="getOrganisationsByActivity",
     * tags={"Activities"},
     * summary="Get organisations by activity",
     * description="Retrieves a list of organisations associated with a specific activity.",
     * @OA\Parameter(
     * name="activity",
     * description="ID of the activity",
     * required=true,
     * in="path",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Organisation")
     * )
     * )
     * )
     */
    public function getOrganisationsByActivity(Activity $activity) {
        return response()->json($activity->organisations);
    }

    /**
     * @OA\Post(
     * path="/api/activities",
     * operationId="storeActivity",
     * tags={"Activities"},
     * summary="Create a new activity",
     * description="Creates a new activity. The depth cannot exceed 3.",
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name", "parent_id"},
     * @OA\Property(property="name", type="string", example="Fish"),
     * @OA\Property(property="parent_id", type="integer", nullable=true, example=1),
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Activity created successfully",
     * @OA\JsonContent(ref="#/components/schemas/Activity")
     * ),
     * @OA\Response(
     * response=400,
     * description="Depth cannot be more than 3"
     * )
     * )
     */
    public function store(CreateActivityRequest $request) {
        $parentId = $request->input('parent_id');

        $parent = Activity::where('id', $parentId)->first();
        
        if ($parent->depth === 3) {
            return response()->json(["message" => 'depth cannot be more than 3'], 400);
        }

        $activity = Activity::create([...$request->validated(), "depth" => $parent->depth+1]);
        return response()->json($activity);
    }
}
