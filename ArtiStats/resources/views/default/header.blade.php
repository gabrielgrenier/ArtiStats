<nav class="navbar justify-content-between" id="header">
    <a class="navbar-brand" href="{{route('home')}}">
        <img src="{{asset('images/logoWhite.png')}}" id="headerLogo"/>
    </a>
    <form class="form-inline" method="post" action="{{action('ApiController@formatSearch')}}">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="headerSearch" name="term">
        @csrf
    </form>
</nav>
