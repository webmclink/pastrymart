<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css','resources/js/app.js'])
    </head>
    <body>
        @auth
            @include('components.layouts.nav')
        @endauth
        <div class="mt-20 max-w-screen-xl mx-auto p-4">
            {{ $slot }}
        </div>
            </body>
</html>
