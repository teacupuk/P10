<header class="mb-4">
    <div class="bg-danger text-white py-2">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <span class="fs-1 fw-bold">P10 Game</span>
            </div>
            <nav class="d-none d-md-flex align-items-center gap-4 fs-3 fw-semibold">
                <a href="/" class="text-white text-decoration-none">Leaderboard</a>
                <div class="dropdown">
                    <button class="btn btn-danger dropdown-toggle fs-3 fw-semibold text-white ms-3"
                            type="button" id="seasonDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Previous
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="seasonDropdown">
                        @foreach($seasons as $s)
                            <li>
                                <a class="dropdown-item" href="{{ route('leaderboard', ['season' => $s->id]) }}">
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