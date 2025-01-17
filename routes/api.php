<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['prefix' => 'v1', 'namespace' => '\App\Http\Controllers\Api\v1'], function () {
    Route::group(['prefix' => 'cards'], function () {
        Route::get('/', 'CardController@paginate');
        Route::post('/', 'CardController@create');
        Route::put('{id}', 'CardController@update');
        Route::delete('{id}', 'CardController@delete');
    });

    Route::group(['prefix' => 'decks'], function () {
        Route::get('/', 'DeckController@paginate');
        Route::post('/', 'DeckController@create');
        Route::put('{id}', 'DeckController@update');
        Route::delete('{id}', 'DeckController@delete');
    });
});
