<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['prefix' => 'v1', 'namespace' => '\App\Http\Controllers\Api\v1'], function () {
    Route::group(['prefix' => 'cards'], function () {
        Route::get('/', 'CardController@paginate')->name('api.v1.cards.paginate');
        Route::post('/', 'CardController@create')->name('api.v1.cards.create');
        Route::put('{id}', 'CardController@update')->name('api.v1.cards.update');
        Route::delete('{id}', 'CardController@delete')->name('api.v1.cards.delete');
    });

    Route::group(['prefix' => 'decks'], function () {
        Route::get('/', 'DeckController@paginate')->name('api.v1.decks.paginate');
        Route::post('/', 'DeckController@create')->name('api.v1.decks.create');
        Route::put('{id}', 'DeckController@update')->name('api.v1.decks.update');
        Route::delete('{id}', 'DeckController@delete')->name('api.v1.decks.delete');
    });
});
