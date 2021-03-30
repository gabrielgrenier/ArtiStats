@extends('default.layoutHome')
@section('title', 'Home')
@section('metaOG')
@endsection
@section('content')
<body>

    <div class="width100 parallax homeParallax pt-lg-5 pt-md-5 pb-2">
        <div class="container-fluid">
            <div class="parallaxCont mt-md-5 pt-md-5">
                <div class="row pt-lg-5 pb-lg-3 mt-lg-5">
                    <div class="col-lg-4 col-md-6 col-sm-10 offset-md-0 offset-sm-1 offset-lg-2">
                        <img  src="{{asset('images/statsBoardPlace.jpg')}}" id="parallaxBoard">
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-10 offset-md-0 offset-sm-1 m-auto pt-md-0 pt-4">
                        <h1>Artists Statistics</h1>
                        <p>
                            AtriStats enable you to find statistic about artists and their songs. Wanna know which words
                            you favorite artist uses more often or how many songs are officialy published by them? Good.
                            Give our platform a try!
                        </p>
                    </div>
                </div>
            </div>
            <div class="parallax-link-cont d-md-block d-none">
                <div class="row pt-lg-4">
                    <div class="col-sm-4 parallaxLink-cont"><a href="#MoreStatistics" class="parallaxLink">More Statistics</a></div>
                    <div class="col-sm-4 parallaxLink-cont"><a href="#OpenSource" class="parallaxLink">Open Source</a></div>
                    <div class="col-sm-4 parallaxLink-cont"><a href="#ArtistInformation" class="parallaxLink">Artist Information</a></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container section-white" id="MoreStatistics">
        <div class="row my-5">
            <div class="col-md-7">
                <img src="{{asset('images/juice_home.png')}}" class="w-100"/>
            </div>
            <div class="col-md-5 m-auto">
                <h1>More Statistics</h1>
                <p>
                    have you ever wanted to know more information about the music
                    you listen to? ArtiStats allows you to find the most used words,
                    the number of songs in an album, the repetition of a particular word and much more!
                </p>
            </div>
        </div>
    </div>
    <div class="container-fluid section-dark" id="OpenSource">
        <div class="container">
            <div class="row py-5">
                <div class="col-md-5 m-auto">
                    <h1>Open Source</h1>
                    <p>
                        ArtiStat is a open source project which means you can find our code on
                        <a href="https://github.com/gabrielgrenier/ArtiStats" target="_blank">GitHub</a>
                        and use it as you please.
                    </p>
                </div>
                <div class="col-md-7 text-center">
                    <img src="{{asset('images/code_home.png')}}" class="w-75 white-border-img d-lg-block d-sm-none"/>
                </div>
            </div>
        </div>
    </div>
    <div class="container section-white" id="ArtistInformation">
        <div class="row my-5">
            <div class="col-md-7">
                <img src="{{asset('images/x17Homepage.png')}}" class="w-100"/>
            </div>
            <div class="col-md-5 m-auto">
                <h1>All of your favorite artists</h1>
                <p>
                    We are using Genius and Spotify's API to bring
                    all the information about your favorite artists.
                </p>
            </div>
        </div>
    </div>

    <div class="width100 parallax cta-parallax">
        <div class="container">
            <div class="row">
                <div class="col-lg-7" style="margin: auto">
                    <h1>Start getting more stats about your music now!</h1>
                </div>
                <div class="col-lg-5 pt-md-5">
                    <div class="cta-cont-top pt-2 pb-1 px-2 text-right">
                        <div class="cta-circle" style="background-color: #6EFF57;"></div>
                        <div class="cta-circle" style="background-color: #FFFF5D;"></div>
                        <div class="cta-circle" style="background-color: #FF514B;"></div>
                    </div>
                    <div class="cta-cont px-4 py-3">
                        <form action="{{url('format/search')}}" method="post">
                            <div class="form-group">
                                <label for="name" class="text-bold">Artist's name</label>
                                <input type="text" class="form-control" id="name" name="term" placeholder="XXXTentacion, Juice WRLD, ...">
                                <small id="emailHelp" class="form-text text-muted">If the artist is on Spotify we'll find it</small>
                                <div class="text-center mt-4">
                                    <input type="submit" value="Search" class="cta-btn" />
                                </div>
                            </div>
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
