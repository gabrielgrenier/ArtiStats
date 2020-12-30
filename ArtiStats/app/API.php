<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Buchin\GoogleImageGrabber\GoogleImageGrabber;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class API extends Model
{
    private $spotify_client_id = 'KEY';
    private $spotify_client_secret = 'SECRET';

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
        $artist =  preg_replace("/[^a-zA-Z0-9]+/", "", $artist);
        $songName =  preg_replace("/[^a-zA-Z0-9]+/", "", $songName);
        return strtolower('https://www.azlyrics.com/lyrics/'.$artist.'/'.$songName.'.html');
    }

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

    //Get an album
    public function getAlbum($id){
        return $this->api->getAlbum($id);
    }

    //Get all songs from an album
    public function getAlbumSongs($id){
        return $this->api->getAlbumTracks($id);
    }

    //Get the lyrics of a song from an artist
    public function getLyrics($artist, $songName){
        $tries = 10;
        $lyrics = '';
        $url = $this->formatSongUrl($artist, $songName);

        //Tries to load the page and get lyrics if it loaded correctly
        while ($tries > 0) {
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $lyricsDiv = $crawler->filter('.col-lg-8')->first();
            if ($lyricsDiv->count() > 0) {
                $lyrics = $lyricsDiv->first()->html();
                $tries = 0;
            }
            $tries--;
        }

        //Format the lyrics
        $lyrics = strip_tags($lyrics);
        $lyrics = explode("\n\r", $lyrics);
        $lyrics = array_filter($lyrics, function ($value) {
            return !is_null($value) && $value !== '';
        })[30];
        $lyrics = str_replace("\n", '<br/>', $lyrics);

        return $lyrics;
    }
}
