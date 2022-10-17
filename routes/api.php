<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;

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

//Route::resource('products',ProductController::class);

//public routes
Route::post('/user/register',[AuthController::class,'register']);
Route::post('user/login',[AuthController::class,'login']);
Route::get('/users',[AuthController::class,'getUser']);

Route::get('/products',[ProductController::class,'index']);
Route::get('/products/{id}',[ProductController::class,'show']);
Route::get('/products/name/{name}',[ProductController::class,'search']);

Route::get('/branch',[BranchController::class,'index']);

//protected routes by sanctum
Route::group(['middleware'=>['auth:sanctum']],function(){
    // Route::post('/products',[ProductController::class,'store']);
    Route::put('/products/{id}',[ProductController::class,'update']);
    Route::delete('/products/{id}',[ProductController::class,'destroy']);

    Route::resource('products',ProductController::class)->only(['store']);

    Route::post('/logout',[AuthController::class,'logout']);

    Route::post('/branch',[BranchController::class,'store']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
