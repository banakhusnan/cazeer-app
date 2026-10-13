<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="theme-color" content="#00B3A4">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/icons/apple-touch-icon.png') }}">
    <title>Masuk · {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pos.css') }}">
</head>
<body class="auth-page">
<a class="auth-brand" href="{{ route('login') }}"><img src="{{ asset('assets/images/logos/cazeer-brand.png') }}" alt="Cazeer by Bana Khusnan"></a>
<main class="auth-shell">
    <section class="auth-card" aria-labelledby="login-title">
        <h1 class="auth-title" id="login-title">Masuk</h1>
        <p class="auth-subtitle">Masuk ke akun Anda untuk mulai mengelola penjualan.</p>
        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="mb-3"><label class="form-label" for="login">Username atau email</label><input class="form-control @error('login') is-invalid @enderror" id="login" name="login" value="{{ old('login') }}" autocomplete="username" required autofocus>@error('login')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="password">Kata sandi</label><div class="password-field"><input class="form-control" id="password" type="password" name="password" autocomplete="current-password" required><button class="password-toggle" id="passwordToggle" type="button" aria-controls="password" aria-pressed="false">Tampilkan</button></div></div>
            <div class="form-check mb-4"><input class="form-check-input" type="checkbox" name="remember" value="1" id="remember"><label class="form-check-label" for="remember">Tetap masuk</label></div>
            <button class="btn btn-primary auth-submit" type="submit">Masuk</button>
        </form>
    </section>
</main>
<script>
const password = document.getElementById('password');
const passwordToggle = document.getElementById('passwordToggle');
passwordToggle.addEventListener('click', () => {
    const visible = password.type === 'text';
    password.type = visible ? 'password' : 'text';
    passwordToggle.textContent = visible ? 'Tampilkan' : 'Sembunyikan';
    passwordToggle.setAttribute('aria-pressed', String(!visible));
});
</script>
</body>
</html>
