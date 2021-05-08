<?php

//use App\Http\Controllers\API\UserController;(1)
use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'users' => API\UserController::class,
]);


/*
Note
 */
//(1): remove this, else it's gonna cause duplicate in path. Ref: https://stackoverflow.com/questions/59735544/laravel-target-class-app-http-controllers-app-http-controllers-apicontroller-d