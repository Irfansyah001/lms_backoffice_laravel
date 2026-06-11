<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/backoffice.css') }}">
    @stack('styles')
</head>
<body>

{{--
    KUMPULAN IKON SVG
    Ikon didefinisikan satu kali di sini, lalu dipanggil
    lewat <svg><use href="#icon-namaikon"></svg> di mana saja.
    Sumber: Feather Icons (feathericons.com) — lisensi MIT.
--}}
<svg xmlns="http://www.w3.org/2000/svg" style="display:none" aria-hidden="true">

    {{-- Dashboard --}}
    <symbol id="icon-dashboard" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
    </symbol>

    {{-- Manajemen User (dua orang) --}}
    <symbol id="icon-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
    </symbol>

    {{-- Rak Buku (lapisan / layers) --}}
    <symbol id="icon-rak" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="12 2 2 7 12 12 22 7 12 2"/>
        <polyline points="2 17 12 22 22 17"/>
        <polyline points="2 12 12 17 22 12"/>
    </symbol>

    {{-- Kategori Buku (label/tag) --}}
    <symbol id="icon-kategori" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
        <line x1="7" y1="7" x2="7.01" y2="7"/>
    </symbol>

    {{-- Manajemen Buku (buku) --}}
    <symbol id="icon-buku" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
    </symbol>

    {{-- Cek Ketersediaan Buku (kaca pembesar) --}}
    <symbol id="icon-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"/>
        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
    </symbol>

    {{-- Anggota (satu orang) --}}
    <symbol id="icon-anggota" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
        <circle cx="12" cy="7" r="4"/>
    </symbol>

    {{-- Peminjaman (dua panah berlawanan) --}}
    <symbol id="icon-peminjaman" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="17 1 21 5 17 9"/>
        <path d="M3 11V9a4 4 0 0 1 4-4h14"/>
        <polyline points="7 23 3 19 7 15"/>
        <path d="M21 13v2a4 4 0 0 1-4 4H3"/>
    </symbol>

    {{-- Pengembalian (panah kembali / balik) --}}
    <symbol id="icon-pengembalian" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="9 14 4 9 9 4"/>
        <path d="M20 20v-7a4 4 0 0 0-4-4H4"/>
    </symbol>

    {{-- Pengaturan Akun (gir/roda gigi) --}}
    <symbol id="icon-pengaturan" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="3"/>
        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
    </symbol>

    {{-- Keluar / Logout (pintu keluar) --}}
    <symbol id="icon-logout" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
        <polyline points="16 17 21 12 16 7"/>
        <line x1="21" y1="12" x2="9" y2="12"/>
    </symbol>

    {{-- Hamburger / Menu (tiga garis) --}}
    <symbol id="icon-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
        <line x1="3" y1="6"  x2="21" y2="6"/>
        <line x1="3" y1="12" x2="21" y2="12"/>
        <line x1="3" y1="18" x2="21" y2="18"/>
    </symbol>

</svg>

{{-- Lapisan gelap saat sidebar terbuka di mobile --}}
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div class="app-shell">

    {{-- =============================================
         SIDEBAR KIRI
         ============================================= --}}
    <aside class="sidebar" id="sidebar">

        {{-- Atas sidebar: logo + tombol hamburger --}}
        <div class="sidebar-top">
            <a class="brand" href="{{ route('dashboard') }}">
                <div class="brand-icon">
                    <svg width="18" height="18"><use href="#icon-buku"></use></svg>
                </div>
                <span class="sidebar-text">
                    <strong class="brand-name">Library Backoffice</strong>
                    <span class="brand-sub">Admin & Pustakawan</span>
                </span>
            </a>
            <button class="sidebar-toggle" id="sidebar-toggle" title="Ciut/buka menu">
                <svg width="18" height="18"><use href="#icon-menu"></use></svg>
            </button>
        </div>

        {{-- Navigasi --}}
        <nav class="nav">

            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
                <svg class="nav-icon"><use href="#icon-dashboard"></use></svg>
                <span class="sidebar-text">Dashboard</span>
            </a>

            {{-- Menu khusus Admin --}}
            @if (auth()->user()->isAdmin())
                <span class="nav-group-label sidebar-text">Admin</span>

                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                   href="{{ route('users.index') }}">
                    <svg class="nav-icon"><use href="#icon-users"></use></svg>
                    <span class="sidebar-text">Manajemen User</span>
                </a>

                <a class="nav-link {{ request()->routeIs('racks.*') ? 'active' : '' }}"
                   href="{{ route('racks.index') }}">
                    <svg class="nav-icon"><use href="#icon-rak"></use></svg>
                    <span class="sidebar-text">Rak Buku</span>
                </a>

                <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                   href="{{ route('categories.index') }}">
                    <svg class="nav-icon"><use href="#icon-kategori"></use></svg>
                    <span class="sidebar-text">Kategori Buku</span>
                </a>

                <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}"
                   href="{{ route('books.index') }}">
                    <svg class="nav-icon"><use href="#icon-buku"></use></svg>
                    <span class="sidebar-text">Manajemen Buku</span>
                </a>
            @endif

            <span class="nav-group-label sidebar-text">Perpustakaan</span>

            <a class="nav-link {{ request()->routeIs('book-availability.*') ? 'active' : '' }}"
               href="{{ route('book-availability.index') }}">
                <svg class="nav-icon"><use href="#icon-search"></use></svg>
                <span class="sidebar-text">Cek Ketersediaan</span>
            </a>

            <a class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}"
               href="{{ route('members.index') }}">
                <svg class="nav-icon"><use href="#icon-anggota"></use></svg>
                <span class="sidebar-text">Anggota</span>
            </a>

            <a class="nav-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}"
               href="{{ route('borrowings.index') }}">
                <svg class="nav-icon"><use href="#icon-peminjaman"></use></svg>
                <span class="sidebar-text">Peminjaman</span>
            </a>

            <a class="nav-link {{ request()->routeIs('returns.*') ? 'active' : '' }}"
               href="{{ route('returns.index') }}">
                <svg class="nav-icon"><use href="#icon-pengembalian"></use></svg>
                <span class="sidebar-text">Pengembalian</span>
            </a>

            <span class="nav-group-label sidebar-text">Akun</span>

            <a class="nav-link {{ request()->routeIs('account.*') ? 'active' : '' }}"
               href="{{ route('account.settings') }}">
                <svg class="nav-icon"><use href="#icon-pengaturan"></use></svg>
                <span class="sidebar-text">Pengaturan Akun</span>
            </a>

        </nav>
    </aside>

    {{-- =============================================
         KONTEN UTAMA (kanan)
         ============================================= --}}
    <div class="main">

        {{-- TOPBAR / NAVBAR --}}
        <header class="topbar">

            {{-- Tombol hamburger (hanya tampil di mobile) --}}
            <button class="topbar-hamburger" id="topbar-hamburger" title="Buka menu">
                <svg width="18" height="18"><use href="#icon-menu"></use></svg>
            </button>

            {{-- Judul & subjudul halaman --}}
            <div class="topbar-title">
                <h1>@yield('title', 'Dashboard')</h1>
                @hasSection('subtitle')
                    <p class="page-subtitle">@yield('subtitle')</p>
                @endif
            </div>

            {{-- Kanan: nama user + role + tombol keluar --}}
            <div class="topbar-right">
                <span class="user-name">{{ auth()->user()->name }}</span>
                <span class="badge {{ auth()->user()->isAdmin() ? 'success' : '' }}">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn-logout" type="submit">
                        <svg width="16" height="16"><use href="#icon-logout"></use></svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        {{-- ISI HALAMAN --}}
        <div class="page-content">
            @include('partials.alerts')
            <div class="container">
                @yield('content')
            </div>
        </div>

    </div>{{-- /.main --}}

</div>{{-- /.app-shell --}}

<script>
/* =====================================================
   SIDEBAR — ciut & buka, simpan status ke localStorage
   ===================================================== */
(function () {
    var sidebar  = document.getElementById('sidebar');
    var overlay  = document.getElementById('sidebar-overlay');
    var btnSide  = document.getElementById('sidebar-toggle');
    var btnTop   = document.getElementById('topbar-hamburger');

    /* --- Desktop: toggle ciut/buka --- */
    function desktopToggle() {
        var isCollapsed = sidebar.classList.toggle('collapsed');
        localStorage.setItem('sb_collapsed', isCollapsed ? '1' : '0');
    }

    /* --- Mobile: buka/tutup laci sidebar --- */
    function mobileOpen() {
        sidebar.classList.add('mobile-open');
        overlay.classList.add('active');
    }

    function mobileClose() {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
    }

    /* Pulihkan status sidebar dari sesi sebelumnya (hanya desktop) */
    if (window.innerWidth > 700 && localStorage.getItem('sb_collapsed') === '1') {
        sidebar.classList.add('collapsed');
    }

    /* Tombol hamburger di dalam sidebar (desktop) */
    btnSide.addEventListener('click', function () {
        if (window.innerWidth > 700) {
            desktopToggle();
        } else {
            /* Di mobile, tombol ini tutup sidebar */
            mobileClose();
        }
    });

    /* Tombol hamburger di topbar (mobile) */
    if (btnTop) {
        btnTop.addEventListener('click', mobileOpen);
    }

    /* Klik overlay menutup sidebar di mobile */
    overlay.addEventListener('click', mobileClose);
})();

/* =====================================================
   COMBOBOX — ubah <select data-combobox> jadi dropdown
   dengan kotak pencarian. Cara pakai: cukup tambahkan
   atribut data-combobox pada elemen <select>.
   ===================================================== */
(function () {
    function makeCombo(select) {
        /* Sembunyikan select asli, tapi tetap ada di DOM
           agar value-nya terkirim lewat form */
        select.style.cssText = 'position:absolute;opacity:0;width:1px;height:1px;';

        var options = Array.from(select.options);
        var selectedOpt = options.find(function (o) { return o.selected && o.value; });

        /* Buat pembungkus */
        var wrap = document.createElement('div');
        wrap.className = 'combo-wrap';
        select.parentNode.insertBefore(wrap, select);
        wrap.appendChild(select);

        /* Input teks pencarian */
        var input = document.createElement('input');
        input.type = 'text';
        input.className = 'combo-input';
        input.placeholder = select.dataset.placeholder || 'Ketik untuk mencari…';
        input.value = selectedOpt ? selectedOpt.text : '';
        input.autocomplete = 'off';
        wrap.insertBefore(input, select);

        /* Daftar pilihan */
        var list = document.createElement('ul');
        list.className = 'combo-list';
        list.hidden = true;
        wrap.appendChild(list);

        function render(items) {
            list.innerHTML = '';
            /* Saring opsi kosong (placeholder) */
            var real = items.filter(function (o) { return o.value !== ''; });
            if (real.length === 0) {
                var li = document.createElement('li');
                li.className = 'combo-empty';
                li.textContent = 'Tidak ada hasil';
                list.appendChild(li);
            } else {
                real.forEach(function (opt) {
                    var li = document.createElement('li');
                    li.textContent = opt.text;
                    if (opt.value === select.value) li.classList.add('selected');
                    li.addEventListener('mousedown', function (e) {
                        e.preventDefault();
                        select.value = opt.value;
                        input.value  = opt.text;
                        list.hidden  = true;
                        /* Picu event change agar logika JS lain bisa bereaksi */
                        select.dispatchEvent(new Event('change'));
                    });
                    list.appendChild(li);
                });
            }
            list.hidden = false;
        }

        input.addEventListener('focus', function () {
            var q = input.value.toLowerCase();
            render(options.filter(function (o) {
                return o.text.toLowerCase().includes(q);
            }));
        });

        input.addEventListener('input', function () {
            select.value = '';
            var q = input.value.toLowerCase();
            render(options.filter(function (o) {
                return o.text.toLowerCase().includes(q);
            }));
        });

        document.addEventListener('click', function (e) {
            if (!wrap.contains(e.target)) list.hidden = true;
        });
    }

    /* Jalankan untuk semua select yang punya atribut data-combobox */
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('select[data-combobox]').forEach(makeCombo);
    });
})();

/* =====================================================
   AUTO-SUBMIT FORM PENCARIAN
   Saat pengguna mengetik di kotak pencarian, form
   langsung disubmit setelah berhenti mengetik (500ms).
   ===================================================== */
(function () {
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.search-form input[type="text"]').forEach(function (inp) {
            var timer;
            inp.addEventListener('input', function () {
                clearTimeout(timer);
                timer = setTimeout(function () {
                    inp.closest('form').submit();
                }, 500);
            });
        });
    });
})();
</script>

@stack('scripts')
</body>
</html>
