<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Goutte\Client;
use Buchin\GoogleImageGrabber\GoogleImageGrabber;


class API extends Model
{
    private $musix_api_key = '858a5945700cb1dc92dd0591fb886f1c';

    public function getMusixApiKey(){
        return $this->musix_api_key;
    }

    public function filterRealArtists($artists){
        $list = [];
        foreach($artists as $artist){
            $name = strtoupper($artist->artist->artist_name);
            if(!(strpos($name, 'FEAT') || strpos($name, 'FEATURING') || strpos($name, '&') || strpos($name, ' AND '))){
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

    public function searchArtist($name){
        $response = Http::get('https://api.musixmatch.com/ws/1.1/artist.search?format=jsonp&callback=callback&q_artist='.$name.'&apikey='.$this->getMusixApiKey());
        if($response->ok()) {
            $images = [];
            $response_json = str_replace('callback(', '', $response->body());
            $response_json = str_replace(');', '', $response_json);
            $response_json = json_decode($response_json)->message->body->artist_list;

            //sort by artist score
            usort($response_json, function($a, $b) {
                return $a->artist->artist_rating > $b->artist->artist_rating ? -1 : 1;
            });

            //get real artists (API is dumb and features count as artists otherwise)
            $artists = $this->filterRealArtists($response_json);

            //get artist imgs
            $images = $this->getArtistsImg($artists);

            $data['artists'] = $artists;
            $data['imgs'] = $images;
            $data['term'] = $name;

            if(sizeof($response_json)>0)
                return $data;
        }
        return null;
    }

    public function getArtist($id){
        $response = Http::get('https://api.musixmatch.com/ws/1.1/artist.get?format=jsonp&callback=callback&artist_id='.$id.'&apikey=858a5945700cb1dc92dd0591fb886f1c'.$this->getApiKey());
        if($response->ok()) {
            $response_json = str_replace('callback(', '', $response->body());
            $response_json = str_replace(');', '', $response_json);
            return json_decode($response_json)->message->body->artist_list;
        }
        return null;
    }
}
