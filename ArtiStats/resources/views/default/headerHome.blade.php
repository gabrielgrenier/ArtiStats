@php
dd($_SERVER['REQUEST_URI']);
@endphp
<nav class="navbar justify-content-between fixed-top" id="headerHome">
    <a class="navbar-brand">
        <img src="{{asset('images/logoWhite.png')}}" id="headerLogo"/>
    </a>
    <form class="form-inline">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="headerSearch">
    </form>
</nav>
