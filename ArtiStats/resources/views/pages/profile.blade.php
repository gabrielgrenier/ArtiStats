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
                <div class="profile-picture" style="background-image: url('{{$artist->images[1]->url}}')"></div>
            </div>
            <div class="text-center">
                <h1 class="text-bold">{{$artist->name}}</h1>
                @if($artist->name === '6ix9ine')
                    <i>AKA 6nitch 9ine</i><br/>
                @endif
                <a href="{{$artist->external_urls->spotify}}" class="profile-link mr-2" target="_blank">Spotify</a> <a href="{{$wikipedia_link}}" class="profile-link" target="_blank">Wikipedia</a>
            </div>

            <div class="my-5">
                <h2 class="text-bold">Information</h2>
                <div class="under-line-block mb-4"></div>
                <p class="information-sub">
                    @if(sizeof($artist->genres) <= 1)
                        <b>Genre : </b>
                    @else
                        <b>Genres : </b>
                    @endif

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

            @if(sizeof($albums) != 0 && $albums != null)
                <div class="my-5">
                    <h2 class="text-bold">Albums</h2>
                    <div class="under-line-block mb-4"></div>
                    <div class="row">
                        @foreach($albums as $album)
                            <div class="col-lg-3 mb-4">
                                <a href="https://www.google.com" class="profile-album-link">
                                    <img src="{{$album->images[0]->url}}" class="albumThumbnail"/>
                                    <h5 class="mt-2">{{$album->name}}</h5>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            @if(sizeof($top_tracks) != 0 && $top_tracks != null)
                <div class="my-3">
                    <h2 class="text-bold">Top Tracks</h2>
                    <div class="under-line-block mb-4"></div>
                    <table class="table table-tracks-hover">
                        <tbody>
                        <?php $index=0 ?>
                        @foreach($top_tracks as $top_track)
                            <?php $index++ ?>
                            <tr>
                                <td style="width: 2.5em;"><h5 class="text-bold top-track-margin">{{$index}}</h5></td>
                                <td><img src="{{$top_track->album->images[2]->url}}" class="top-track-img"></td>
                                <td>
                                    <h5 class="text-bold top-track-margin"><a href="#" class="profile-link">{{$top_track->name}}</a></h5>
                                </td>
                                <td>
                                    @if($top_track->explicit === true)
                                        <div class="top-track-margin">
                                            <span class="explicit-cont">Explicit</span>
                                        </div>
                                    @endif
                                </td>
                                <td><h5 class="text-bold top-track-margin">{{explode('-', $top_track->album->release_date)[0]}}</h5></td>
                                <td><a href="https://open.spotify.com/album/{{$top_track->album->id}}" class="top-track-play-btn" target="_blank"><i class="fas fa-play-circle"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection
