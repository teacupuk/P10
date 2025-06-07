<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <x-application-head />
    <body class="antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
        <div class="min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
            @include('layouts.public.navigation')
            <main>
                {{ $slot }}
            </main>
            
        </div>
    </body>
</html>