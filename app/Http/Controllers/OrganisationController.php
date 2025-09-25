<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Building;
use App\Models\Organisation;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;


/**
 * @OA\Info(
 * version="1.0.0",
 * title="Building Management API",
 * description="API documentation for the Organisations resource.",
 * )
 *
 * @OA\PathItem(path="/api/organisations")
 */
class OrganisationController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/building/{building}/organisations",
     * operationId="getOrganisationsInBuilding",
     * tags={"Organisations"},
     * summary="Get all organisations within a specific building",
     * description="Retrieves a list of organisations located in a given building.",
     * @OA\Parameter(
     * name="building",
     * description="ID of the building",
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
     * ),
     * @OA\Response(
     * response=404,
     * description="Building not found"
     * )
     * )
     */
    public function getOrganisationsInBuilding(Building $building) {
        return response()->json($building->organisations);
    }

    /**
     * @OA\Get(
     * path="/api/organisations/{organisation}",
     * operationId="showOrganisation",
     * tags={"Organisations"},
     * summary="Get a single organisation",
     * description="Returns a single organisation with its associated building and activities.",
     * @OA\Parameter(
     * name="organisation",
     * description="ID of the organisation",
     * required=true,
     * in="path",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(ref="#/components/schemas/FullOrganisation")
     * )
     * )
     */
    public function show(Organisation $organisation) {
        return response()->json($organisation->load('building', 'activities'));
    }

    /**
     * @OA\Get(
     * path="/api/organisations/search",
     * operationId="searchOrganisations",
     * tags={"Organisations"},
     * summary="Search for organisations by name",
     * description="Returns a list of organisations that match the given name query.",
     * @OA\Parameter(
     * name="name",
     * description="Search query for organisation name",
     * required=false,
     * in="query",
     * @OA\Schema(type="string")
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
    public function searchOrganisations(Request $request) {
        $query = $request->input('name');
        if (!$query) {
            return response()->json([]);
        }
        return response()->json(Organisation::where('name', 'like', '%' . $query . '%')->get());
    }
    
    /**
     * @OA\Get(
     * path="/api/organisations/area/rect",
     * operationId="getOrganisationsInRectArea",
     * tags={"Organisations"},
     * summary="Get organisations within a rectangular geographic area",
     * description="Returns a list of organisations whose buildings are within the specified latitude and longitude bounds.",
     * @OA\Parameter(
     * name="min_lat",
     * description="Minimum latitude of the search area",
     * required=true,
     * in="query",
     * @OA\Schema(type="number", format="float")
     * ),
     * @OA\Parameter(
     * name="max_lat",
     * description="Maximum latitude of the search area",
     * required=true,
     * in="query",
     * @OA\Schema(type="number", format="float")
     * ),
     * @OA\Parameter(
     * name="min_lng",
     * description="Minimum longitude of the search area",
     * required=true,
     * in="query",
     * @OA\Schema(type="number", format="float")
     * ),
     * @OA\Parameter(
     * name="max_lng",
     * description="Maximum longitude of the search area",
     * required=true,
     * in="query",
     * @OA\Schema(type="number", format="float")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/FullOrganisation")
     * )
     * )
     * )
     */
    public function getOrganisationsInRectArea(Request $request) {
        $minLat = $request->input('min_lat');
        $maxLat = $request->input('max_lat');
        $minLng = $request->input('min_lng');
        $maxLng = $request->input('max_lng');

        $buildings = Building::whereBetween('latitude', [$minLat, $maxLat])
            ->whereBetween('longitude', [$minLng, $maxLng])
            ->get();

        $buildingsId = $buildings->pluck('id');

        $organisations = Organisation::whereIn('building_id', $buildingsId)->with('building', 'activities')->get();

        return response()->json($organisations);
    }

    /**
     * @OA\Get(
     * path="/api/organisations/area/radius",
     * operationId="getOrganisationsInRadius",
     * tags={"Organisations"},
     * summary="Get organisations within a radius geographic area",
     * description="Returns a list of organisations whose buildings are within the specified radius from estimated dot.",
     * @OA\Parameter(
     * name="center_lat",
     * description="latitude of center",
     * required=true,
     * in="query",
     * @OA\Schema(type="number", format="float")
     * ),
     * @OA\Parameter(
     * name="center_lng",
     * description="longitude of center",
     * required=true,
     * in="query",
     * @OA\Schema(type="number", format="float")
     * ),
     * @OA\Parameter(
     * name="radius",
     * description="radius of search",
     * required=true,
     * in="query",
     * @OA\Schema(type="number", format="float")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/FullOrganisation")
     * )
     * )
     * )
     */
    public function getOrganisationsInRadius(Request $request)
    {
        $request->validate([
            'center_lat' => 'required|numeric',
            'center_lng' => 'required|numeric',
            'radius' => 'required|numeric|min:0',
        ]);

        $centerLat = $request->input('center_lat');
        $centerLng = $request->input('center_lng');
        $radius = $request->input('radius');

        $earthRadius = 6371;

        $organisations = Organisation::select('organisations.*', DB::raw("
            ($earthRadius * acos(
                cos(radians($centerLat))
                * cos(radians(buildings.latitude))
                * cos(radians(buildings.longitude) - radians($centerLng))
                + sin(radians($centerLat))
                * sin(radians(buildings.latitude))
            )) AS distance"
        ))
        ->join('buildings', 'organisations.building_id', '=', 'buildings.id')
        ->having('distance', '<=', $radius)
        ->with('building', 'activities')
        ->orderBy('distance')
        ->get();

        return response()->json($organisations);
    }

     /**
     * @OA\Get(
     * path="/api/organisations/search/by/activity",
     * operationId="getOrganisationsByActivityTypes",
     * tags={"Organisations"},
     * summary="Get organisations by activity type",
     * description="Retrieves a list of organisations that are associated with a specific activity and its child activities types",
     * @OA\Parameter(
     * name="activity_type",
     * description="Name of the activity type to search for",
     * required=true,
     * in="query",
     * @OA\Schema(type="string")
     * ),
     * @OA\Response(
     * response=200,
     * description="Successful operation",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/FullOrganisation")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Invalid activity type"
     * )
     * )
     */
    public function getOrganisationsByActivityTypes(Request $request) {
        $activityType = $request->input('activity_type');

        if (empty($activityType)) {
            return response()->json([]);
        }
        $rootActivityId = Activity::where('name', $activityType)->first();

        if (empty($rootActivityId)) {
            return response()->json([], 401);
        }

        $activityTypeIds = $this->getDescendantIds($rootActivityId->id);

        $organisations = Organisation::with('building', 'activities')->whereHas('activities', function ($query) use ($activityTypeIds) {
            $query->whereIn('activity_id', $activityTypeIds);
        })->get();

        return response()->json($organisations);
    }
    
    private function getDescendantIds(int $parentId): array
    {
        $descendantIds = [];
        $children = Activity::where('parent_id', $parentId)->pluck('id')->toArray();
        
        foreach ($children as $childId) {
            $descendantIds[] = $childId;
            $descendantIds = array_merge($descendantIds, $this->getDescendantIds($childId));
        }

        return $descendantIds;
    }
}
