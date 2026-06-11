<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/backoffice.css') }}">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <a class="brand" href="{{ route('dashboard') }}">
                <strong>Library Backoffice</strong>
                <span>Admin & Pustakawan</span>
            </a>

            <nav class="nav">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>

                @if (auth()->user()->isAdmin())
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">Manajemen User</a>
                    <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">Manajemen Buku</a>
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">Kategori Buku</a>
                @endif

                <a class="nav-link {{ request()->routeIs('book-availability.*') ? 'active' : '' }}" href="{{ route('book-availability.index') }}">Cek Ketersediaan Buku</a>
                <a class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}" href="{{ route('members.index') }}">Anggota</a>
                <a class="nav-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}" href="{{ route('borrowings.index') }}">Peminjaman</a>
                <a class="nav-link {{ request()->routeIs('returns.*') ? 'active' : '' }}" href="{{ route('returns.index') }}">Pengembalian</a>
                <a class="nav-link {{ request()->routeIs('account.*') ? 'active' : '' }}" href="{{ route('account.settings') }}">Pengaturan Akun</a>
            </nav>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="nav-link logout" type="submit">Logout</button>
            </form>
        </aside>

        <main class="main">
            <header class="topbar">
                <div>
                    <h1>@yield('title', 'Dashboard')</h1>
                    <p>@yield('subtitle', 'Ringkasan data perpustakaan.')</p>
                </div>
                <div class="user-box">
                    <span>{{ auth()->user()->name }}</span>
                    <span class="badge success">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
            </header>

            @include('partials.alerts')

            <div class="container">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
