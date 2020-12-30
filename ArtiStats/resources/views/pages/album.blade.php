@extends('default.layout')
@section('title', $album->name)
@section('metaOG')
@endsection
@section('content')
    <div class="container mt-5 text-montserrat">
        <div class="profile-body p-3">
            <div class="px-3">
                <div class="media mb-4">
                    <img class="album-picture" src="{{$album->images[1]->url}}"/>
                    <div class="media-body pl-4">
                        <h1 class="text-bold">{{$album->name}}</h1>
                        <h3>
                            By :
                            @php $index=0; @endphp
                            @foreach($album->artists as $artist)
                                <a href="{{url('format/profile/'.$artist->name)}}" class="album-artist-link">
                                    {{$artist->name}}@if($index!==sizeof($album->artists)-1), @endif
                                </a>
                                @php $index++; @endphp
                            @endforeach
                        </h3>
                        <h4>
                            {{\Carbon\Carbon::parse($album->release_date)->isoFormat('LL')}}
                        </h4>
                    </div>
                </div>

                <div class="my-3" style="overflow: auto;">
                    <h2 class="text-bold">Tracks</h2>
                    <div class="under-line-block mb-4"></div>
                    <table class="table table-tracks-hover w-100">
                        <tbody>
                        @php
                            $index=0;
                            $artistName = preg_replace("/[^a-zA-Z0-9]+/", '', $album->artists[0]->name);
                        @endphp
                        @foreach($songs as $track)
                            @php
                                $index++;
                                $trackName = preg_replace("/[^a-zA-Z0-9]+/", '', $track->name);
                            @endphp
                            <tr>
                                <td style="width: 3em;"><h5 class="text-bold top-track-margin">{{$index}}</h5></td>
                                <td>
                                    <h5 class="text-bold top-track-margin"><a href="{{url($artistName.'/'.$album->id.'/'.$trackName.'/lyrics')}}" class="profile-link">{{$track->name}}</a></h5>
                                </td>
                                <td>
                                    @if($track->explicit === true)
                                        <div class="top-track-margin">
                                            <span class="explicit-cont">Explicit</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-right"><a href="https://open.spotify.com/album/{{$album->id}}" class="top-track-play-btn" target="_blank"><i class="fas fa-play-circle"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="cont-footer mb-5 py-2 text-center">
            <p class="mb-0">{{$album->label}}</p>
        </div>
    </div>
@endsection
