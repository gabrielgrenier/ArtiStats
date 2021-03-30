<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Buchin\GoogleImageGrabber\GoogleImageGrabber;
use Illuminate\Support\Facades\Http;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class API extends Model
{
    private $spotify_client_id = 'KEY';
    private $spotify_client_secret = 'KEY';
    private $musix_match_id = 'KEY';

    private $session;
    private $api;

    public function setupApi(){
        $this->session = new Session(
            $this->spotify_client_id,
            $this->spotify_client_secret
        );
        $this->session->requestCredentialsToken();
        $accessToken = $this->session->getAccessToken();

        $this->api = new SpotifyWebAPI();
        $this->api->setAccessToken($accessToken);
    }

    public function filterRealArtists($artists){
        $list = [];

        foreach($artists as $artist){
            $name = strtoupper($artist->name);
            if(!(strpos($name, 'FEAT') || strpos($name, 'FEATURING')
                || strpos($name, '&') || strpos($name, ' AND ') || strpos($name, 'BY')
                || strpos($name, 'FT.') || strpos($name, 'PROD.') || strpos($name, 'PROD'))){

                if($artist->popularity>=50)
                    $list[] = $artist;
            }
        }
        return $list;
    }

    public function getArtistsImg($artists){
        $images = [];
        $client = new Client();

        foreach($artists as $artist){
            $crawler = $client->request('GET', 'https://www.google.com/search?q='.$artist->artist->artist_name.'&tbm=isch');
            $images[] = $crawler->filter('div > img')->first()->attr('src');
        }

        return $images;
    }

    public function formatSongUrl($artist, $songName){
        //Find a better solution
        if(str_contains($songName, 'feat'))
            $songName = substr($songName, 0, strpos($songName, 'feat'));
        if(str_contains($songName, 'ft'))
            $songName = substr($songName, 0, strpos($songName, 'ft'));
        if(str_contains($songName, 'Feat'))
            $songName = substr($songName, 0, strpos($songName, 'feat'));
        if(str_contains($songName, 'Ft'))
            $songName = substr($songName, 0, strpos($songName, 'ft'));

        if($songName[strlen($songName)-1] == '-')
            $songName = rtrim($songName, "-");

        return strtolower('https://genius.com/'.$artist.'-'.$songName.'-lyrics');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // SPOTIFY
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Get artist from a name
    public function searchArtist($name){
        $results = $this->api->search($name, 'artist');
        $artists = $this->filterRealArtists($results->artists->items);

        if(sizeof($artists)<=0){
            return null;
        }

        $data['artists'] = $artists;
        $data['term'] = $name;

        return $data;
    }

    //Get an artist from his name
    public function getArtist($name){
        $results = $this->api->search($name, 'artist');
        $artist = $this->filterRealArtists($results->artists->items)[0];

        return $artist;
    }

    //Get the albums of an artist
    public function getAlbums($id){
        $albums = $this->api->getArtistAlbums($id, ['album_type' => 'album'])->items;
        $albums_unique = [];
        foreach($albums as $album){
            $count = 0;
            foreach($albums_unique as $album_unique){
                if($album->name===$album_unique->name)
                    $count++;
            }
            if($count===0)
                $albums_unique[] = $album;
        }

        return $albums_unique;
    }

    //Get artist recommendation
    public function getRelatedArtists($id){
        return $this->api->getArtistRelatedArtists($id);
    }

    //Get the top tracks of an artist
    public function getArtistTopTrack($id){
        return $this->api->getArtistTopTracks($id, ['country' => 'US']);
    }

    //Get an album
    public function getAlbum($id){
        return $this->api->getAlbum($id);
    }

    //Get all songs from an album
    public function getAlbumSongs($id){
        return $this->api->getAlbumTracks($id);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // MUSIX MATCH
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Get a artist from MusixMatch's API
    public function getMusixArtist($name){
        $response = Http::get('https://api.musixmatch.com/ws/1.1/artist.search?format=jsonp&callback=callback&q_artist='.$name.'&page_size=50&apikey='.$this->musix_match_id);
        $response_json = json_decode($this->formatMusixResponse($response))->message->body->artist_list;

        if(sizeof($response_json)<= 0)
            return null;

        $artist = null;
        foreach($response_json as $artist_temp){
            if(strtoupper($artist_temp->artist->artist_name) == strtoupper($name))
                $artist = $artist_temp->artist;
        }

        if($artist != null)
            return $artist;

        //If there was no perfect match
        usort($response_json, function($a, $b) {
            return $a->artist->artist_rating > $b->artist->artist_rating ? -1 : 1;
        });

        return $response_json[0]->artist;
    }

    //Get a track from MusixMatch's API
    public function getMusixTrack($artist, $track_name){
        $response = Http::get('https://api.musixmatch.com/ws/1.1/track.search?format=jsonp&callback=callback&q_track='.$track_name.'&q_artist='.$artist->artist_name.'&f_artist_id='.$artist->artist_id.'&s_track_rating=true&apikey='.$this->musix_match_id);
        $response_json = json_decode($this->formatMusixResponse($response))->message->body->track_list;

        if(sizeof($response_json)<= 0)
            return null;

        $track = null;
        foreach($response_json as $track_temp){
            if(strtoupper($track_temp->track->track_name) == strtoupper($track_name) && $track_temp->track->artist_id == $artist->artist_id)
                $track = $track_temp->track;
        }

        if($track != null)
            return $track;

        //If there was no perfect match
        return $response_json[0]->track;
    }

    //Get the lyrics of a song from an artist
    public function getMusixLyrics($track){
        if(!$track->has_lyrics)
            return null;

        $response = Http::get('https://api.musixmatch.com/ws/1.1/track.lyrics.get?format=jsonp&callback=callback&track_id='.$track->track_id.'&commontrack_id='.$track->commontrack_id.'&apikey=858a5945700cb1dc92dd0591fb886f1c');
        $response_lyrics = json_decode($this->formatMusixResponse($response))->message->body->lyrics;

        return $response_lyrics->lyrics_body;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // OTHERS
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Format the reponse of MusixMatch's API
    public function formatMusixResponse($response){
        $response_json = str_replace('callback(', '', $response->body());
        $response_json = str_replace(');', '', $response_json);
        return $response_json;
    }

    //Get artist's image from google, not really used anymore
    public function getProfilePictures($name){
        $images_list = GoogleImageGrabber::grab($name);
        while(sizeof($images_list)<5)
            $images_list = GoogleImageGrabber::grab($name);

        $images[] = $images_list[0]['url'];
        $images[] = $images_list[1]['url'];

        /*
        $index1 = rand(0, 10);
        $images[] = $images_list[$index1]['url'];

        $index2 = rand(0, 10);
        if($index2===$index1){
            while($index2===$index1){
                $index2 = rand(0, 10);
            }
        }
        $images[] = $images_list[$index2]['url'];
        */

        return $images;
    }



    public function getLyrics($artist, $songName){
        $tries = 10;
        $lyrics = '';
        $desc = '';
        $url = $this->formatSongUrl($artist, $songName);

        //Tries to load the page and get lyrics if it loaded correctly
        while ($tries > 0) {
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $lyricsDiv = $crawler->filter('.Lyrics__Container-sc-1ynbvzw-2')->first();
            $descDiv = $crawler->filter('.SongDescription__Content-sc-615rvk-1 > .RichText__Container-oz284w-0')->first();

            if ($lyricsDiv->count() > 0) {
                $lyrics = preg_replace('#<a.*?>(.*?)</a>#i', '\1', $lyricsDiv->first()->html());
                $lyrics = preg_replace('#<span.*?>(.*?)</span>#i', '\1', $lyrics);
                $desc = preg_replace('#<a.*?>(.*?)</a>#i', '\1', $descDiv->first()->html());

                $tries = 0;
            }
            $tries--;
        }

        return [$lyrics, $desc];
    }


}
