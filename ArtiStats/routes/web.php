<?php

use Goutte\Client;
use Illuminate\Support\Facades\Http;
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

Route::get('/test', function(){
    $client = new Client();
    $crawler = $client->request('GET', 'https://genius.com/Kanye-west-father-stretch-my-hands-pt-1-lyrics');
    $lyricsDiv = $crawler->filter('.SongDescription__Content-sc-615rvk-1 > .RichText__Container-oz284w-0')->first();
    $lyrics = preg_replace('#<a.*?>(.*?)</a>#i', '\1', $lyricsDiv->first()->html());
    $lyrics = preg_replace('#<span.*?>(.*?)</span>#i', '\1', $lyrics);
    dd($lyrics);
   dd("test");
});
