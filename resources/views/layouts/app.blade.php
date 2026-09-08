<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Research Grant System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-page">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ auth()->check() ? route('dashboard') : url('/') }}">
                Research Grant System
            </a>
            <div class="navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        @if (auth()->user()->hasRole('Admin'))
                            <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Manage Users</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('academicians.index') }}">Manage Academicians</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('grants.index') }}">Manage Grants</a></li>
                        @endif
                        @if (auth()->user()->hasRole('Leader'))
                            <li class="nav-item"><a class="nav-link" href="{{ route('leader.dashboard') }}">My Grants</a></li>
                        @endif
                        @if (auth()->user()->hasRole('Academic'))
                            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">My Grants</a></li>
                        @endif
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4 app-content">
        @yield('content')
    </main>

    <footer class="bg-dark text-white text-center py-3 mt-4 app-footer">
        <p>© {{ date('Y') }} Research Grant Management System</p>
    </footer>
</body>
</html>