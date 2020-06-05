<?php


//home
Route::view('/', 'pages.home')->name('home');

//SEARCH
Route::get('search/{name}', 'ApiController@searchArtist')->name('ArtistSearch');
Route::get('search', 'ApiController@emptySearch')->name('ArtistSearch');
Route::post('format/search', 'ApiController@formatSearch')->name('FormatSearch');

//PROFILE
Route::get('profile/{name}', 'ApiController@profileArtist')->name('ArtistSearch');
Route::get('format/profile/{name}', 'ApiController@formatProfile')->name('FormatSearch');
