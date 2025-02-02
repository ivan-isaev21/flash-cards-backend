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

    Route::group(['prefix' => 'deck-items'], function () {
        Route::get('{deckId}', 'DeckItemController@paginate')->name('api.v1.deck-items.paginate');
        Route::post('/', 'DeckItemController@create')->name('api.v1.deck-items.create');
        Route::put('{id}', 'DeckItemController@update')->name('api.v1.deck-items.update');
        Route::delete('{id}', 'DeckItemController@delete')->name('api.v1.deck-items.delete');
    });

    Route::group(['prefix' => 'study'], function () {
        Route::get('{deckId}/get-card-to-review', 'StudyController@getCardToReview')->name('api.v1.study.getCardToReview');
        Route::put('{deckItemId}/submit-review', 'StudyController@submitReview')->name('api.v1.study.submitReview');
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', 'AuthController@register')->name('api.v1.auth.register');
        Route::put('request-reset-password', 'AuthController@requestResetPassword')->name('api.v1.auth.requestResetPassword');
        Route::put('reset-password', 'AuthController@resetPassword')->name('api.v1.auth.resetPassword');
    });
});
