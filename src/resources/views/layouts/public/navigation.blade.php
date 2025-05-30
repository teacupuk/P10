<header class="mb-4" >
    <div class="bg-danger text-white py-2" >
        <div class="container d-flex justify-content-between align-items-center" style="height: 56px;">
            <div class="d-flex align-items-center" style="height: 100%;">
                <a href="{{ route('public.app') }}" class="d-flex align-items-center" style="height: 100%;">
                    <img src="/images/p10_logo.png" class="img-fluid h-100" style="object-fit: contain;">
                </a>
            </div>
            <nav class="d-none d-md-flex align-items-center gap-4 fs-3 fw-semibold">
                <a href="/" class="text-white text-decoration-none">Leaderboard</a>
                <div class="dropdown">
                    <button class="btn btn-danger dropdown-toggle fs-3 fw-semibold text-white ms-3"
                            type="button" id="seasonDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Seasons
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="seasonDropdown">
                        @foreach($seasons as $s)
                            <li>
                                <a class="dropdown-item" href="/leaderboard/{{ $s->id }}">
                                    {{ $s->id }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <a href="/rules" class="text-white text-decoration-none">Rules</a>

            </nav>
        </div>
    </div>
</header>