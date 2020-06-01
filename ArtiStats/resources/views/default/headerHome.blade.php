<nav class="navbar justify-content-between fixed-top" id="headerHome">
    <a class="navbar-brand">
        <img src="{{asset('images/logoWhite.png')}}" id="headerLogo"/>
    </a>
    <form class="form-inline" method="post" action="{{action('MusixController@formatSearch')}}">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="headerSearch" name="term">
        @csrf
    </form>
</nav>
