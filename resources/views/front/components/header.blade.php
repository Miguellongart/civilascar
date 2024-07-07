<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{route('front.home')}}">ASCAR</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> Menu
        </button>

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active"><a href="{{route('front.home')}}" class="nav-link">Incio</a></li>
                <li class="nav-item"><a href="{{route('front.about')}}" class="nav-link">Sobre Nosotros</a></li>
                <li class="nav-item"><a href="{{route('front.school')}}" class="nav-link">Escuelita</a></li>
                {{-- <li class="nav-item"><a href="donate.html" class="nav-link">Donate</a></li>
                <li class="nav-item"><a href="blog.html" class="nav-link">Blog</a></li>
                <li class="nav-item"><a href="gallery.html" class="nav-link">Gallery</a></li>
                <li class="nav-item"><a href="event.html" class="nav-link">Events</a></li> --}}
                <li class="nav-item"><a href="{{route('front.tournament')}}" class="nav-link">Liga Cafetera</a></li>
                <li class="nav-item"><a href="{{route('front.contact')}}" class="nav-link">Contact</a></li>
                <li class="nav-item"><a href="{{route('login')}}" class="nav-link">Login</a></li>
            </ul>
        </div>
    </div>
</nav>