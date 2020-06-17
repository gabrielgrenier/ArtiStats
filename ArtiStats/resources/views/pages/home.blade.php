@extends('default.layoutHome')
@section('title', 'Home')
@section('metaOG')
@endsection
@section('content')
<body>

    <div class="width100 parallax homeParallax">
        <div class="container-fluid">
            <div class="parallaxCont">
                <div class="row">
                    <div class="col-lg-2"></div><!-- pad -->
                    <div class="col-lg-4">
                        <img  src="{{asset('images/statsBoardPlace.jpg')}}" id="parallaxBoard">
                    </div>
                    <div class="col-lg-4" id="parallaxInfo">
                        <h1>Artists Statistics</h1>
                        <p>
                            AtriStats enable you to find statistic about artists and their songs. Wanna know which words
                            you favorite artist uses more often or how many songs are officialy published by them? Good.
                            Give our platform a try!
                        </p>
                    </div>
                </div>
            </div>
            <div id="parallaxLinkCont">
                <div class="row">
                    <div class="col-lg-4"><a href="#MoreStatistics" class="parallaxLink">More Statistics</a></div>
                    <div class="col-lg-4"><a href="#OpenSource" class="parallaxLink">Open Source</a></div>
                    <div class="col-lg-4"><a href="#ArtistInformation" class="parallaxLink">Artist Information</a></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container section-white" id="MoreStatistics">
        <div class="row my-5">
            <div class="col-md-7">
                <img src="{{asset('images/juice_home.png')}}" class="w-100"/>
            </div>
            <div class="col-md-5">
                <h1>More Statistics</h1>
                <p>
                    have you ever wanted to know more information about the music
                    you listen to? ArtiStats allows you to know the most used words,
                    the number of songs in an album, the repetition of a particular word and much more!
                </p>
            </div>
        </div>
    </div>
    <div class="container-fluid section-dark" id="OpenSource">
        <div class="container">
            <div class="row py-5">
                <div class="col-md-5">
                    <h1>Open Source</h1>
                    <p>
                        ArtiStat is a open source project which means you can find our code on
                        <a href="https://github.com/gabrielgrenier/ArtiStats" target="_blank">GitHub</a>
                        and use it as you please.
                    </p>
                </div>
                <div class="col-md-7 text-center">
                    <img src="{{asset('images/code_home.png')}}" class="w-75 white-border-img"/>
                </div>
            </div>
        </div>
    </div>
    <div class="container section-white" id="ArtistInformation">
        <div class="row my-5">
            <div class="col-md-7">
                <img src="{{asset('images/x17Homepage.png')}}" class="w-100"/>
            </div>
            <div class="col-md-5">
                <h1>All of your favorite artists</h1>
                <p>
                    We are using Musixmatch's API to bring
                    all the informations about your favorite artists.
                </p>
            </div>
        </div>
    </div>
    <!-- add call to action -->
</body>
@endsection
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $("a").on('click', function(event) {
                if (this.hash !== "") {
                    event.preventDefault();
                    var hash = this.hash;
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 800, function(){
                        window.location.hash = hash;
                    });
                }
            });
        });
    </script>
@endpush
