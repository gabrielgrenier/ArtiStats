@extends('default.layout')
@section('title', 'Home')
@section('metaOG')
@endsection
@section('content')
    <div class="container">
        <div class="page-title">Results</div>
        @if($data !== null)
            @php $index = 0; @endphp
            @foreach($data['artists'] as $artist)
                <a href="#" class="search-artist-link">
                    <div class="search-artist-cont">
                        <p><img style="background-image: url({{$data['imgs'][$index]}});" class="search-artist-img" />{{$artist->artist->artist_name}}</p>
                    </div>
                </a>
                @php $index++; @endphp
            @endforeach
        @else
            <p class="search-artist-not-found">We couldn't find any result for what you are looking for.</p>
        @endif
    </div>
@endsection
