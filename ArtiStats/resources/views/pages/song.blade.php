@extends('default.layout')
@section('title', $songName)
@section('metaOG')
@endsection
@section('content')

<div class="container my-5 text-montserrat">
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

            <div>
                <h2 class="text-bold">{{$songName}}</h2>
                <div class="under-line-block mb-4"></div>
                <div class="mt-n4">
                    {!! $lyrics !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
