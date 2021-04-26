<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Buchin\GoogleImageGrabber\GoogleImageGrabber;
use Illuminate\Support\Facades\Http;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Symfony\Component\BrowserKit\HttpBrowser;

class API extends Model {

    private $spotify_client_id = '';
    private $spotify_client_secret = '';

    private $genius_client_id = '';
    private $genius_client_secret = '';
    private $genius_access_token = '';

    private $session;
    private $spotify_api;
    private $genius_api;

    function __construct(){
        $this->session = new Session(
            $this->spotify_client_id,
            $this->spotify_client_secret
        );
        $this->session->requestCredentialsToken();
        $accessToken = $this->session->getAccessToken();

        $this->spotify_api = new SpotifyWebAPI();
        $this->spotify_api->setAccessToken($accessToken);

        $authentication = new \Genius\Authentication\OAuth2(
            $this->genius_client_id,
            $this->genius_client_secret,
            'https://github.com/gabrielgrenier/ArtiStats',
            new \Genius\Authentication\Scope([
                \Genius\Authentication\Scope::SCOPE_ME,
                \Genius\Authentication\Scope::SCOPE_CREATE_ANNOTATION,
                \Genius\Authentication\Scope::SCOPE_MANAGE_ANNOTATION,
                \Genius\Authentication\Scope::SCOPE_VOTE,
            ]),
            null,
        );
        $authentication->setAccessToken($this->genius_access_token);
        $this->genius_api = new \Genius\Genius($authentication);
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
        $results = $this->spotify_api->search($name, 'artist');
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
        $results = $this->spotify_api->search($name, 'artist');
        $artist = $this->filterRealArtists($results->artists->items)[0];

        return $artist;
    }

    //Get the albums of an artist
    public function getAlbums($id){
        $albums = $this->spotify_api->getArtistAlbums($id, ['album_type' => 'album'])->items;
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
        return $this->spotify_api->getArtistRelatedArtists($id);
    }

    //Get the top tracks of an artist
    public function getArtistTopTrack($id){
        return $this->spotify_api->getArtistTopTracks($id, ['country' => 'US']);
    }

    //Get an album
    public function getAlbum($id){
        return $this->spotify_api->getAlbum($id);
    }

    //Get all songs from an album
    public function getAlbumSongs($id){
        return $this->spotify_api->getAlbumTracks($id);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // GENIUS
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function searchGenius(String $term){
        return $this->genius_api->getSearchResource()->get($term)->response->hits;
    }

    public function findGeniusSongUrl(String $artist, String $songName){
        return $this->searchGenius($artist.' '.$songName)[0]->result->url;
    }

    public function getLyrics($artist, $songName){
        $tries = 20;
        $lyrics = '';
        $desc = '';
        $url = $this->findGeniusSongUrl($artist, $songName);

        //Tries to load the page and get lyrics if it loaded correctly
        while ($tries > 0) {
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $lyricsDiv = $crawler->filter('.Lyrics__Container-sc-1ynbvzw-2')->first();
            $descDiv = $crawler->filter('.SongDescription__Content-sc-615rvk-1 > .RichText__Container-oz284w-0')->first();

            if ($lyricsDiv->count() > 0) {
                $lyrics = preg_replace('#<a.*?>(.*?)</a>#i', '\1', $lyricsDiv->first()->html());
                $lyrics = preg_replace('#<span.*?>(.*?)</span>#i', '\1', $lyrics);

                try{
                    $desc = preg_replace('#<a.*?>(.*?)</a>#i', '\1', $descDiv->first()->html());
                } catch (Exception $e){
                    $desc = null;
                }

                $tries = 0;
            }
            $tries--;
        }

        return [$lyrics, $desc];
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






}
