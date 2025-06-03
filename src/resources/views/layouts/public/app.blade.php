<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="//unpkg.com/alpinejs" defer></script>

        <!-- CSS -->
         @vite(['resources/css/app.css'])
        <style>
            body {
                font-family: 'Barlow Condensed', sans-serif;
                font-size: 1rem;
                letter-spacing: 0.3px;
            }
            .accordion-button:not(.collapsed):hover {
                background-color: #e10600 !important;
                color: white !important;
            }
            .accordion-button:hover {
                background-color: #e10600 !important;
                color: white !important;
            }
            .table-f1 {
                border-collapse: separate;
                border-spacing: 0;
                overflow: hidden;
                border-radius: 6px;
            }
            .table-f1 thead {
                background-color: #000;
                color: #fff;
                font-weight: 700;
            }
            .table-f1 thead th:first-child {
                border-top-left-radius: 6px;
            }
            .table-f1 thead th:last-child {
                border-top-right-radius: 6px;
            }
            .table-f1 tbody tr:nth-child(odd) {
                background-color: #f8f8f8;
            }
            .table-f1 tbody tr:nth-child(even) {
                background-color: #eeeeee;
            }
            .card,
            .accordion-item {
                box-shadow: none !important;
                border-radius: 0;
            }
            .table td,
            .table th {
                padding: 0.75rem 1rem;
                vertical-align: middle;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
        <div class="min-h-screen bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
            @include('layouts.public.navigation')
            <main>
                {{ $slot }}
            </main>
            
        </div>
    </body>
</html>