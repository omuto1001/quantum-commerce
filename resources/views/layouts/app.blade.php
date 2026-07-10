{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Quantum Commerce') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-slate-900 via-purple-900 to-orange-800 min-h-screen">
    <div class="min-h-screen">

        {{-- Sidebar, fixed to the left --}}
        @include('layouts.sidebar')

        {{-- Main content area, pushed right so it doesn't sit under the sidebar --}}
        <div class="ml-64">

            {{-- Page heading, if a view sets one via @section('header') --}}
            @isset($header)
                <header class="bg-white shadow p-6">
                    {{ $header }}
                </header>
            @endisset

            <main class="p-8 min-h-screen">
                {{-- Flash success message, shown on any page after a redirect --}}
                @if (session('success'))
                    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>