<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class API extends Model
{
    private $api_key = 'YOUR_KEY';

    public function getApiKey(){
        return $this->api_key;
    }

    public function filterRealArtists($artists){

    }

    public function searchArtist($name){
        $response = Http::get('https://api.musixmatch.com/ws/1.1/artist.search?format=jsonp&callback=callback&q_artist='.$name.'&apikey='.$this->getApiKey());
        if($response->ok()) {
            $images = [];
            $artists = [];
            $response_json = str_replace('callback(', '', $response->body());
            $response_json = str_replace(');', '', $response_json);
            $response_json = json_decode($response_json)->message->body->artist_list;

            //sort by artist score
            usort($response_json, function($a, $b) {
                return $a->artist->artist_rating > $b->artist->artist_rating ? -1 : 1;
            });

            //get real artists and imgs
            foreach($response_json as $artist){
                $name = strtoupper($artist->artist->artist_name);
                if(!(strpos($name, 'FEAT') || strpos($name, 'FEATURING') || strpos($name, '&') || strpos($name, ' AND '))){
                    $images[] = 'https://static01.nyt.com/images/2018/06/19/arts/19xxx/19xxx-superJumbo-v2.jpg';
                    $artists[] = $artist;
                }
            }

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
