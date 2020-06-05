<?php


//home
Route::view('/', 'pages.home')->name('home');

//API
Route::get('search/{name}', 'ApiController@searchArtist')->name('ArtistSearch');
Route::get('search', 'ApiController@emptySearch')->name('ArtistSearch');
Route::post('format/search', 'ApiController@formatSearch')->name('FormatSearch');

