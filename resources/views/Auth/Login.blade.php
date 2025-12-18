<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Akademik Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            max-width: 900px;
        }

        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right {
            padding: 50px;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-card row g-0">
            <div class="col-md-5 login-left">
                <h2 class="mb-4"><i class="fas fa-graduation-cap"></i> Sistem Akademik</h2>
                <p class="lead">Selamat datang di portal akademik sekolah. Silakan login untuk mengakses sistem.</p>
                <hr class="bg-white">
                <div class="mt-4">
                    <p><i class="fas fa-check-circle"></i> Lihat Jadwal Pelajaran</p>
                    <p><i class="fas fa-check-circle"></i> Lihat Nilai & Raport</p>
                    <p><i class="fas fa-check-circle"></i> Pembayaran Online</p>
                </div>
            </div>
            <div class="col-md-7 login-right">
                <h3 class="mb-4">Login</h3>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Username (Admin/Ortu) atau NIS (Siswa)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="username"
                                class="form-control @error('username') is-invalid @enderror"
                                value="{{ old('username') }}" required autofocus>
                        </div>
                        @error('username')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Ingat Saya</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </form>

                <hr class="my-4">
                <div class="text-center text-muted small">
                    <p class="mb-1">Panduan Login:</p>
                    <p><strong>Admin</strong> → Username & Password Admin</p>
                    <p><strong>Orang Tua</strong> → Username akun orang tua</p>
                    <p><strong>Siswa</strong> → NIS & Password siswa</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
