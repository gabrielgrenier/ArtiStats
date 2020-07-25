@extends('default.layout')
@section('title', 'Search')
@section('metaOG')
@endsection
@section('content')
    <div class="container">
        <div class="page-title mb-0">Results</div>
        <div class="under-line-block-lg mb-4"></div>
        @if($data !== null)
            @php $index = 0; @endphp
            @foreach($data['artists'] as $artist)
                <a href="{{url('format/profile/'.$artist->name)}}" class="search-artist-link">
                    <div class="search-artist-cont">
                        <p><img style="background-image: url({{$artist->images[2]->url}});" class="search-artist-img" />{{$artist->name}}</p>
                    </div>
                </a>
                @php $index++; @endphp
            @endforeach
        @else
            <p class="search-artist-not-found">We couldn't find any result for what you are looking for.</p>
        @endif
    </div>
@endsection
