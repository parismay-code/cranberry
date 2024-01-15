<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ExtraCategoriesController;
use App\Http\Controllers\IngredientsController;
use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
});

Route::get('/categories', [CategoriesController::class, 'get']);
Route::post('/categories', [CategoriesController::class, 'create']);
Route::patch('/categories/{category}', [CategoriesController::class, 'update']);
Route::delete('/categories/{category}', [CategoriesController::class, 'delete']);

Route::get('/extra-categories', [ExtraCategoriesController::class, 'get']);
Route::post('/extra-categories', [ExtraCategoriesController::class, 'create']);
Route::patch('/extra-categories/{extraCategory}', [ExtraCategoriesController::class, 'update']);
Route::delete('/extra-categories/{extraCategory}', [ExtraCategoriesController::class, 'delete']);

Route::get('/ingredients', [IngredientsController::class, 'get']);
Route::post('/ingredients', [IngredientsController::class, 'create']);
Route::patch('/ingredients/{ingredient}', [IngredientsController::class, 'update']);
Route::delete('/ingredients/{ingredient}', [IngredientsController::class, 'delete']);

Route::get('/products', [ProductsController::class, 'get']);
Route::post('/products/{category}', [ProductsController::class, 'create']);
Route::patch('/products/{product}', [ProductsController::class, 'update']);
Route::patch('/products/{product}/add-extra/{extraCategory}', [ProductsController::class, 'addExtra']);
Route::patch('/products/{product}/remove-extra', [ProductsController::class, 'removeExtra']);
Route::patch('/products/{product}/add-ingredient/{ingredient}', [ProductsController::class, 'addIngredient']);
Route::patch('/products/{product}/remove-ingredient/{ingredient}', [ProductsController::class, 'removeIngredient']);
Route::delete('/products/{product}', [ProductsController::class, 'delete']);
