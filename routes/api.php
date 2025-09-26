<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\OrganisationController;
use Illuminate\Support\Facades\Route;

Route::get('/building/{building}/organisations', [OrganisationController::class, 'getOrganisationsInBuilding']);
Route::get('/organisations/search', [OrganisationController::class, 'searchOrganisations']);
Route::apiResource('organisations', OrganisationController::class);
Route::apiResource('activities', ActivityController::class);
Route::get('/organisations/search/by/activity', [OrganisationController::class, 'getOrganisationsByActivityTypes']);
Route::get('/activities/{activity}/organisations', [ActivityController::class, 'getOrganisationsByActivity']);
Route::get('/organisations/area/rect', [OrganisationController::class, 'getOrganisationsInRectArea']);
Route::get('/organisations/area/radius', [OrganisationController::class, 'getOrganisationsInRadius']);