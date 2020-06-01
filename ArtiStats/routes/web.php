<?php


//home
Route::view('/', 'pages.home')->name('home');

//API
Route::get('search/{name}', 'MusixController@searchArtist')->name('ArtistSearch');
Route::get('search', 'MusixController@emptySearch')->name('ArtistSearch');
Route::post('format/search', 'MusixController@formatSearch')->name('FormatSearch');

