<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <!-- Scripts -->
        @vite(['resources/js/app.js'])

        <!-- CSS -->
         @vite(['resources/css/app.css'])
    </head>
    <body class="antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
        <div class="min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
            @include('layouts.public.navigation')
            <main>
                {{ $slot }}
            </main>
            
        </div>
    </body>
</html>