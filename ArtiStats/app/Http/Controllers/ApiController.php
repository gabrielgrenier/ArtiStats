<?php

namespace App\Http\Controllers;

use App\API;
use Illuminate\Http\Request;

class ApiController extends Controller{

    //SEARCH
    public function searchArtist($name){
        $api = new API();
        $data = $api->searchArtist($name);


        if($data !== null && sizeof($data['artists']) === 1)
            return redirect('format/profile/'.$data['artists'][0]->artist->artist_name);

        return view('pages.search', ['data' => $data]);
    }

    public function emptySearch(){
        return view('pages.search', ['data' => null]);
    }

    public function formatSearch(){
        $term = str_replace(' ', '-', request('term'));
        return redirect('search/'.$term);
    }

    //PROFILE
    public function profileArtist($name){
        $api = new API();
        $artist = $api->getArtist($name);
        $images = $api->getProfilePictures($name);

        return view('pages.profile', ['artist' => $artist, 'imgs' => $images]);
    }

    public function formatProfile($name){
        $term = str_replace(' ', '-', $name);
        return redirect('profile/'.$term);
    }

}
