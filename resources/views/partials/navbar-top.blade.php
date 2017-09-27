<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand d-md-none" href="#">{{ config('app.name') }}</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTop">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarTop">
            <form class="form-inline">
                <input class="form-control mr-2" type="text" placeholder="Vyhledávání publikací">
                <button class="btn btn-outline-success" type="submit">Vyhledat</button>
            </form>
            <ul class="navbar-nav ml-auto">
                @if(Auth::check())
                    <li class="nav-item mr-2">
                        <a class="nav-link" href="#">Administrace</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="#" role="button">Odhlásit</a>
                    </li>
                @else
                    <li class="nav-item mr-2">
                        <a class="nav-link" href="#">Přihlásit</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="#" role="button">Registrovat</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>