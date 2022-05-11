<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
        <!-- Styles -->

        <link type="text/css" rel="stylesheet" href="{{asset('/css/aura.css')}}">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        
        @livewireStyles

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased bg-transparent">
        <div class="min-h-screen bg-ccuffs h-100">

            <!-- Page Heading -->
            <header class="bg-ccuffs-light shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header ?? '' }}
                </div>
            </header>

            <!-- Page Content -->
            <main class="h-100">
                {{ $slot }}
            </main>
        </div>

        @stack('modals')
        
        @livewireScripts
        <!-- @bukScripts(true) -->
    </body>
</html>
