<?php

namespace App\Http\Controllers;

use App\API;

class ApiController extends Controller{

    //SEARCH
    public function searchArtist($name){
        $api = new API();
        $api->setupApi();
        $data = $api->searchArtist($name);


        if($data !== null && sizeof($data['artists']) === 1)
            return redirect('format/profile/'.$data['artists'][0]->name);

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
        $api->setupApi();
        $artist = $api->searchArtist($name)['artists'];

        if(sizeof($artist)>1){
            $term = str_replace(' ', '-', $name);
            return redirect('search/'.$term);
        }

        $artist = $artist[0];
        $images = $api->getProfilePictures($name);

        return view('pages.profile', ['artist' => $artist, 'imgs' => $images]);
    }

    public function formatProfile($name){
        $term = str_replace(' ', '-', $name);
        return redirect('profile/'.$term);
    }

}
