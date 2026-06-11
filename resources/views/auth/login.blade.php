<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/backoffice.css') }}">
</head>
<body>
    <main class="auth-page">
        <section class="auth-card">
            <h1>Login</h1>
            <p>Masuk ke Library Management System Backoffice.</p>

            @include('partials.alerts')

            <form method="POST" action="{{ route('login.store') }}" class="grid">
                @csrf
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <label class="actions">
                    <input type="checkbox" name="remember" value="1" style="width:auto; min-height:auto;">
                    Ingat saya
                </label>

                <button class="btn" type="submit">Login</button>
            </form>

            <p style="margin-top:18px;">Akun Pustakawan dibuat oleh Admin melalui menu Manajemen User.</p>
            <p class="muted">Demo: admin@lms.test / password</p>
        </section>
    </main>
</body>
</html>
