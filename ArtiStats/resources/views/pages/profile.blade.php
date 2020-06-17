@extends('default.layout')
@section('title', $artist->name)
@section('metaOG')
@endsection
@section('content')
    <div class="container mt-5">
        <div class="profile-header" style="background-image: url({{$artist->images[0]->url}}) !important;">
        </div>
        <div class="profile-body p-3 mb-5">
            <div class="profile-picture" style="background-image: url({{$artist->images[1]->url}}) !important;"></div>
            <div class="text-center">
                <h1>{{$artist->name}}</h1>
                @if($artist->name === '6ix9ine')
                    <i>AKA 6nitch 9ine</i>
                @endif
            </div>
        </div>
    </div>
@endsection
