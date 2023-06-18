<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/admin/login', [App\Http\Controllers\API\AdminController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum','cors']], function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });

    Route::post('/admin/register', [App\Http\Controllers\API\AdminController::class, 'register']);

    //API route for hosts and tenants
    Route::get('/admin/all', [App\Http\Controllers\API\AdminController::class, 'all']);

    //API route for hosts and tenants
    Route::get('/tenants', [App\Http\Controllers\API\TenantController::class, 'index']);

    //API route for one tenants
    Route::get('/tenant/view/{id}', [App\Http\Controllers\API\TenantController::class, 'view']);

    // API route for logout user
    Route::post('/admin/logout', [App\Http\Controllers\API\AdminController::class, 'logout']);

    //API route create new tenant
    Route::post('/tenant/create', [App\Http\Controllers\API\TenantController::class, 'create']);

    //API route update tenant
    Route::put('/tenant/update/{id}', [App\Http\Controllers\API\TenantController::class, 'update']);

    //API route delete tenant
    Route::get('/tenant/delete/{id}', [App\Http\Controllers\API\TenantController::class, 'delete']);


});


Route::fallback(function(){
    return response()->json([
        'status'    => false,
        'message'   => 'Endpoint Not Found.',
    ], 404);
});
