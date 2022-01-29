@extends('default.layout')
@section('title', 'Search')
@section('metaOG')
@endsection
@section('content')
    <div class="container" @if(sizeof($data['artists']) < 7) style="position: relative;min-height: 87vh;" @endif>
        <div class="page-title mb-0">Results</div>
        <div class="under-line-block-lg mb-4"></div>
        @if(sizeof($data['artists']) > 0)
            <p class="search-term">Showing results for <strong>{{$data['term']}}</strong>.</p>
            @foreach($data['artists'] as $artist)
                <a href="{{url('format/profile/'.$artist->name)}}" class="search-artist-link">
                    <div class="search-artist-cont">
                        <p><img style="background-image: url({{$artist->images[2]->url}});" class="search-artist-img" />{{$artist->name}}</p>
                    </div>
                </a>
            @endforeach
        @else
            <p class="search-term">We couldn't find any result for <strong>{{$data['term']}}</strong>.</p>
        @endif
    </div>
@endsection
