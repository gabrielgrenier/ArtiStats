<?php

namespace App\Http\Controllers;

use App\API;
use http\Env\Request;

class ApiController extends Controller{

    //SEARCH

    //Search an artist
    public function searchArtist($name){
        $api = new API();
        $api->setupApi();
        $data = $api->searchArtist($name);


        if($data !== null && sizeof($data['artists']) === 1)
            return redirect('format/profile/'.$data['artists'][0]->name);

        return view('pages.search', ['data' => $data]);
    }

    //Search if no term
    public function emptySearch(){
        return view('pages.search', ['data' => null]);
    }

    //Format search url
    public function formatSearch(){
        $term = str_replace(' ', '-', request('term'));
        return redirect('search/'.$term);
    }

    //PROFILE

    //Show profile
    public function profileArtist($name){
        $api = new API();
        $api->setupApi();
        $artist = $api->searchArtist($name)['artists'];

        if(sizeof($artist)>1){
            $term = str_replace(' ', '-', $name);
            return redirect('search/'.$term);
        }

        $artist = $artist[0];
        $albums = $api->getAlbums($artist->id);
        $top_tracks = $api->getArtistTopTrack($artist->id)->tracks;
        $related_artists = array_slice($api->getRelatedArtists($artist->id)->artists, 0, 6, true);

        $total_songs = 0;
        foreach($albums as $album){
            $total_songs += $album->total_tracks;
        }
        $wikipedia_link = 'https://en.wikipedia.org/wiki/'.str_replace(' ', '_', $artist->name);
        return view('pages.profile', [
            'artist' => $artist,
            'albums' => $albums,
            'total_songs' => $total_songs,
            'wikipedia_link' => $wikipedia_link,
            'top_tracks' => $top_tracks,
            'related_artists' => $related_artists
        ]);
    }

    //Format the profile's url
    public function formatProfile($name){
        $term = str_replace(' ', '-', $name);
        return redirect('profile/'.$term);
    }

    //ALBUMS
    public function showAlbumPage($id){
        $api = new API();
        $api->setupApi();

        $album = $api->getAlbum($id);
        $songs = $api->getAlbumSongs($id)->items;
        return view('pages.album', ['album' => $album, 'songs' => $songs]);
    }

    //SONGS
    public function showSongPage($artist, $albumId, $songName){
        $api = new API();
        $api->setupApi();

        //find a way to format the song name / artist here instead of the page
        //add 404 if song not found

        $album = $api->getAlbum($albumId);
        $lyrics = $api->getLyrics($artist, $songName);
        $lyrics = preg_replace("/<a href=.*?>(.*?)<\/a>/","",$lyrics);
        return view('pages.song', ['lyrics' => $lyrics, 'album' => $album, 'songName' => $songName]);
    }

}
