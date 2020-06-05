<?php

namespace App\Http\Controllers;

use App\API;
use Illuminate\Http\Request;

class ApiController extends Controller{

    public function searchArtist($name){
        $api = new API();
        $data = $api->searchArtist($name);

        //if size of artist = 1 go to the profil page

        return view('pages.search', ['data' => $data]);
    }

    public function emptySearch(){
        return view('pages.search', ['data' => null]);
    }

    public function formatSearch(){
        $term = str_replace(' ', '-', request('term'));
        return redirect('search/'.$term);
    }
}
