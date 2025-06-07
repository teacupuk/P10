<nav x-data="{ open: false }" class="bg-red-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center h-full">
            <a href="{{ route('public.app') }}" class="flex items-center h-full">
                <img src="/images/p10_logo.png" alt="P10 Logo" class="h-full object-contain">
            </a>
        </div>

        <!-- Desktop Nav -->
        <div class="hidden md:flex items-center gap-6 text-lg font-semibold">
            <a href="/" class="hover:underline">Leaderboard</a>

            <x-dropdown align="left" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center gap-1 font-semibold px-3 py-2 text-white hover:text-gray-200 focus:outline-none transition ease-in-out duration-150">
                        <span>Seasons</span>
                        <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20">
                            <path d="M5.25 7.5L10 12.25L14.75 7.5H5.25Z"/>
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    @foreach($seasons as $s)
                        <x-dropdown-link :href="'/leaderboard/' . $s->id">
                            {{ $s->id }}
                        </x-dropdown-link>
                    @endforeach
                </x-slot>
            </x-dropdown>

            <a href="/rules" class="hover:underline">Rules</a>
        </div>

        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center">
            <button @click="open = !open" class="text-white focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden px-4 pb-4">
        <a href="/" class="block py-2 text-white hover:underline">Leaderboard</a>
        <div class="mt-2">
            <div class="font-semibold">Seasons</div>
            <ul class="mt-1 space-y-1">
                @foreach($seasons as $s)
                    <li>
                        <a href="/leaderboard/{{ $s->id }}" class="block px-2 py-1 text-white hover:bg-red-700 rounded transition">
                            {{ $s->id }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
        <a href="/rules" class="block mt-2 py-2 text-white hover:underline">Rules</a>
    </div>
</nav>