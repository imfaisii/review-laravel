<?php

use App\Http\Controllers\Auth\Permissions\PermissionController;
use App\Http\Controllers\Auth\Permissions\RoleController;
use App\Http\Controllers\UserController;
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

require __DIR__ . '/auth.php';


/*================= PROTECTED ROUTES =================*/
Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['as' => 'permissions.'], function () {
        Route::apiResource('/permissions', PermissionController::class);
        Route::get('/get-permissions', [PermissionController::class, 'getUserPermissions'])->name('user');
    });

    Route::group(['as' => 'roles.'], function () {
        Route::apiResource('/roles', RoleController::class);
    });

    Route::group(['as' => 'users.'], function () {
        Route::apiResource('/users', UserController::class);
    });
});
