<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatter Box</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex min-h-screen">

    {{-- Sidebar kiri --}}
    @include('partials.sidebar')

    {{-- Konten utama --}}
    <main class="flex-1 ml-64 p-6 overflow-y-auto">
        @yield('content')
    </main>

</body>
</html>