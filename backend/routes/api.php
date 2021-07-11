<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArchieveController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;

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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'

], function ($router) {
    // file
    Route::get('/all', [ArchieveController::class, 'all']);
    Route::post('/add', [ArchieveController::class, 'add']);
    Route::get('/file', [ArchieveController::class, 'file']);
    // tag
    Route::get('/tag', [TagController::class, 'getTag']);
    Route::post('/tag', [TagController::class, 'addTag']);
    Route::delete('/tag', [TagController::class, 'deleteTag']);
    // comment
    Route::get('/comment', [CommentController::class, 'getComment']);
    Route::post('/comment', [CommentController::class, 'addComment']);
});
