@extends('default.layout')
@section('title', $artist->artist_name)
@section('metaOG')
@endsection
@section('content')
    <div class="container mt-5">
        <div class="profile-header" style="background-image: url({{$imgs[1]}}) !important;">
        </div>
        <div class="profile-body p-3 mb-5">
            <div class="profile-picture" style="background-image: url({{$imgs[0]}}) !important;"></div>
            <div class="text-center">
                <h1>{{$artist->artist_name}}</h1>
                @if($artist->artist_name === '6ix9ine')
                    <i>AKA 6nitch 9ine</i>
                @endif
            </div>
        </div>
    </div>
@endsection
