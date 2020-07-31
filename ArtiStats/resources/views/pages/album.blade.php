@extends('default.layout')
@section('title', $album->name)
@section('metaOG')
@endsection
@section('content')
    <div class="container mt-5 text-montserrat">
        <div class="profile-body p-3">
            <div class="row">
                <div class="media px-3">
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
                            {{$album->release_date}}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="cont-footer mb-5 py-2 text-center">
            <p class="mb-0">{{$album->label}}</p>
        </div>
    </div>
@endsection
