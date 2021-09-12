<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BreedController;
use App\Models\Breed;
use App\Models\Park;
use App\Models\User;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/breeds', 'App\Http\Controllers\BreedController@index');
Route::get('/breed/random', 'App\Http\Controllers\BreedController@randomBreed');
Route::get('/breed/{breed_id}', 'App\Http\Controllers\BreedController@show');
Route::get('/breed/{breed_id}/image', 'App\Http\Controllers\BreedController@getImageByBreed');
Route::get('/test', 'App\Http\Controllers\BreedController@readFromCache');
Route::put('/user/{user_id}/associate', function(Request $request, $user_id) {
    $user = User::findorFail($user_id);
    $bodyContent = json_decode($request->getContent(), true);
    $userable_id = $bodyContent['userable_id'];
    $userable_type = $bodyContent['userable_type'];
    if($userable_type == "park")
    {
        $park = Park::findorFail($userable_id);
        $park->users()->attach($user);
    } else if($userable_type == "breed"){
        $breed = Breed::findorFail($userable_id);
        $breed->users()->attach($user);
    }
    return $user;
});
Route::put('/park/{park_id}/breed', function(Request $request, $park_id) {
    $park = User::findorFail($park_id);
    $bodyContent = json_decode($request->getContent(), true);
    $parkable_id = $bodyContent['parkable_id'];
    $breed = Breed::findorFail($parkable_id);
    $breed->parks()->attach($park);
    return $park;
});

