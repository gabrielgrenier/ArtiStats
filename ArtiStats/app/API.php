<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Goutte\Client;
use Buchin\GoogleImageGrabber\GoogleImageGrabber;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class API extends Model
{
    private $spotify_client_id = 'key';
    private $spotify_client_secret = 'key';

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

}
