@extends('default.layout', ['noFooter' => true, 'errorPage' => true])
@section('title', '404 | ArtiStats')
@section('metaOG')
@endsection
@section('content')
@php
    $index = rand(0, 2);

    $name = "";
    switch($index){
        case 0:
            $name = "kanye.gif";
            break;
        case 1:
            $name = "ski.gif";
            break;
        case 2:
            $name = "rocky.gif";
            break;
    }
@endphp
<div class="container text-center">
    <h1 class="error-code text-white mb-0">404</h1>
    <img src="{{asset('images/gifs/'.$name)}}">
    <p class="error-text text-white text-bold mt-5">
        You seem lost, click <a class="text-white" href="{{url('/')}}"><u>here</u></a> to go back to the site
    </p>
</div>
@endsection
