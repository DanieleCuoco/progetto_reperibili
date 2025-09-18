<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistema Reperibilità - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('modifiche.css') }}?v={{ time() }}">
    @yield('styles')
</head>
<body>
    <header>
        <div class="logo">
            <h1>Sistema Reperibilità</h1>
        </div>
        <div class="user-info">
            <span>Admin</span>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </header>

    <div class="container">
        <div class="sidebar">
            <ul>
                <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i> Dashboard</a>
                </li>
                <li class="{{ request()->routeIs('admin.reperibili.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reperibili.index') }}"><i class="bi bi-people"></i> Reperibili</a>
                </li>
                <li class="{{ request()->routeIs('admin.reparti.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reparti.index') }}"><i class="bi bi-building"></i> Reparti</a>
                </li>
                <li class="{{ request()->routeIs('admin.modifiche.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.modifiche.index') }}"><i class="bi bi-check2-square"></i> Gestione Modifiche</a>
                </li>
            </ul>
        </div>

        <main>
            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>