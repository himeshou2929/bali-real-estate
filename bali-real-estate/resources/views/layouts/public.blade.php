<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bali Real Estate</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="max-w-6xl mx-auto p-6">
  <header class="mb-6 flex items-center justify-between">
    <a href="{{ route('properties.index') }}" class="text-xl font-bold">Bali Real Estate</a>
    <nav class="text-sm">
      @auth
        <a href="{{ route('admin.properties.index') }}" class="underline">Admin</a>
        <span class="ml-2">{{ Auth::user()->name }}</span>
      @else
        <a href="{{ route('login') }}" class="underline">Log in</a>
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="underline ml-2">Register</a>
        @endif
      @endauth
    </nav>
  </header>

  <main>
    @yield('content')
  </main>

  <footer class="mt-10 text-sm text-gray-500">&copy; {{ date('Y') }}</footer>
</body>
</html>