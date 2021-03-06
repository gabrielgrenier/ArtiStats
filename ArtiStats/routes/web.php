<?php
use Illuminate\Support\Facades\Route;

//home
Route::view('/', 'pages.home')->name('home');

//SEARCH
Route::get('search/{name}', 'ApiController@searchArtist')->name('ArtistSearch');
Route::get('search', 'ApiController@emptySearch')->name('ArtistSearch');
Route::post('format/search', 'ApiController@formatSearch')->name('FormatSearch');

//PROFILE
Route::get('profile/{name}', 'ApiController@profileArtist')->name('ArtistSearch');
Route::get('format/profile/{name}', 'ApiController@formatProfile')->name('FormatSearch');
Route::post('getAlbums', 'ApiController@getAlbums')->name('ajaxGetAlbums');

//ALBUM
Route::get('album/{id}', 'ApiController@showAlbumPage')->name('AlbumPage');

Route::get('{artist}/{albumId}/{songName}/lyrics', 'ApiController@showSongPage')->name('SongPage');
