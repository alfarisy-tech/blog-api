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
//route login
Route::post('/login', [App\Http\Controllers\Api\Auth\LoginController::class, 'index']);

//group route with middleware "auth"
Route::group(['middleware' => 'auth:api'], function () {

    //logout
    Route::post('/logout', [App\Http\Controllers\Api\Auth\LoginController::class, 'logout']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//group route with prefix "admin"
Route::prefix('admin')->group(function () {

    //group route with middleware "auth:api"
    Route::group(['middleware' => 'auth:api'], function () {

        //dashboard
        Route::get('/dashboard', App\Http\Controllers\Api\Admin\DashboardController::class);

        //permissions
        Route::get('/permissions', [\App\Http\Controllers\Api\Admin\PermissionController::class, 'index'])
            ->middleware('permission:permissions.index');

        //permissions all
        Route::get('/permissions/all', [\App\Http\Controllers\Api\Admin\PermissionController::class, 'all'])
            ->middleware('permission:permissions.index');

        //roles all
        Route::get('/roles/all', [\App\Http\Controllers\Api\Admin\RoleController::class, 'all'])
            ->middleware('permission:roles.index');

        //roles
        Route::apiResource('/roles', App\Http\Controllers\Api\Admin\RoleController::class)
            ->middleware('permission:roles.index|roles.store|roles.update|roles.delete');

        //users
        Route::apiResource('/users', App\Http\Controllers\Api\Admin\UserController::class)
            ->middleware('permission:users.index|users.store|users.update|users.delete');

        //categories all
        Route::get('/categories/all', [\App\Http\Controllers\Api\Admin\CategoryController::class, 'all'])
            ->middleware('permission:categories.index');

        //Categories
        Route::apiResource('/categories', App\Http\Controllers\Api\Admin\CategoryController::class)
            ->middleware('permission:categories.index|categories.store|categories.update|categories.delete');

        //Posts
        Route::apiResource('/posts', App\Http\Controllers\Api\Admin\PostController::class)
            ->middleware('permission:posts.index|posts.store|posts.update|posts.delete');

        //Sliders
        Route::apiResource('/sliders', App\Http\Controllers\Api\Admin\SliderController::class, ['except' => ['create', 'show', 'update']])
            ->middleware('permission:sliders.index|sliders.store|sliders.delete');
    });
});
