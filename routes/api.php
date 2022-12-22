<?php

use App\Http\Controllers\API\AboutController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EducationController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\SkillController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login',  'login');
});
Route::controller(AboutController::class)->group(function () {
    Route::get('edit_about', 'edit_about');
    Route::post('update_about/{id}', 'update_about');
});

Route::controller(ServiceController::class)->group(function () {
    Route::get('get_all_services', 'get_all_services');
    Route::post('create_service', 'create_service');
    Route::put('update_service/{id}', 'update_service');
    Route::delete('delete_service/{id}', 'delete_service');
});
Route::controller(SkillController::class)->group(function () {
    Route::get('/get_all_skills', 'get_all_skills');
    Route::post('/create_skill', 'create_skill');
    Route::put('/update_skill/{id}', 'update_skill');
    Route::delete('/delete_skill/{id}', 'delete_skill');
});

Route::controller(EducationController::class)->group(function () {
    Route::get('/get_all_education', 'get_all_education');
    Route::post('/create_education', 'create_education');
    Route::put('/update_education/{id}', 'update_education');
    Route::delete('/delete_education/{id}', 'delete_education');
});
