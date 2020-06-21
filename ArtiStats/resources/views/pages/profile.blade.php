@extends('default.layout')
@section('title', $artist->name)
@section('metaOG')
@endsection
@section('content')
    <div class="container mt-5 text-montserrat">
        <div class="profile-header" style="background-image: url({{$artist->images[0]->url}}) !important;">
            <div class="profile-header-filter" style="z-index: 0"></div>
        </div>
        <div class="profile-body p-3 mb-5">
            <div class="text-center">
                <img class="profile-picture" src="{{$artist->images[1]->url}}" />
            </div>
            <div class="text-center">
                <h1 class="text-bold">{{$artist->name}}</h1>
                @if($artist->name === '6ix9ine')
                    <i>AKA 6nitch 9ine</i>
                @endif
                <a href="{{$artist->external_urls->spotify}}" class="profile-link mr-2" target="_blank">Spotify</a> <a href="{{$wikipedia_link}}" class="profile-link" target="_blank">Wikipedia</a>
            </div>

            <div class="my-5">
                <h2 class="text-bold">Information</h2>
                <div class="under-line-block mb-4"></div>
                <p class="information-sub">
                    <b>Genres : </b>
                    @php($index=0)
                    @foreach($artist->genres as $genre)
                        @if($index!==sizeof($artist->genres)-1)
                            {{$genre}},
                        @else
                            {{$genre}}
                        @endif
                        @php($index++)
                    @endforeach
                </p>
                <p class="information-sub">
                    <b>Spotify Follower Count : </b>
                    {{number_format($artist->followers->total)}}
                </p>
                <p class="information-sub">
                    <b>Spotify Popularity : </b>
                    {{$artist->popularity}}
                </p>
                <p class="information-sub">
                    <b>Album Count : </b>
                    {{sizeof($albums)}}
                </p>
                <p class="information-sub">
                    <b>Album Songs Count : </b>
                    {{$total_songs}}
                </p>
            </div>

            <div class="my-5">
                <h2 class="text-bold">Albums</h2>
                <div class="under-line-block mb-4"></div>
                <div class="row">
                    @foreach($albums as $album)
                        <div class="col-lg-3 mb-4">
                            <a href="https://www.google.com" class="profile-album-link">
                                <img src="{{$album->images[0]->url}}" class="albumThumbnail"/>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection
