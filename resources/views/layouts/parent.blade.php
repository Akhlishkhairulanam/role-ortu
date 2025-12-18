{{-- resources/views/layouts/Parent.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Portal Orang Tua</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 0;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-md-2 sidebar p-0">
                <div class="p-4 text-center border-bottom border-light">
                    <i class="fas fa-graduation-cap fa-3x mb-2"></i>
                    <h5>Portal Orang Tua</h5>
                </div>

                <nav class="nav flex-column p-3">

                    <a class="nav-link {{ request()->routeIs('Parent.dashboard') ? 'active' : '' }}"
                        href="{{ route('Parent.dashboard') }}">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>

                    <a class="nav-link {{ request()->routeIs('Parent.profil-anak') ? 'active' : '' }}"
                        href="{{ route('Parent.profil-anak') }}">
                        <i class="fas fa-user me-2"></i> Profil Anak
                    </a>

                    <a class="nav-link {{ request()->routeIs('Parent.jadwal.*') ? 'active' : '' }}"
                        href="{{ route('Parent.jadwal.index') }}">
                        <i class="fas fa-calendar-alt me-2"></i> Jadwal Pelajaran
                    </a>

                    <a class="nav-link {{ request()->routeIs('Parent.nilai.*') ? 'active' : '' }}"
                        href="{{ route('Parent.nilai.index') }}">
                        <i class="fas fa-chart-line me-2"></i> Nilai & Raport
                    </a>

                    <a class="nav-link {{ request()->routeIs('Parent.pembayaran.*') ? 'active' : '' }}"
                        href="{{ route('Parent.pembayaran.index') }}">
                        <i class="fas fa-money-bill-wave me-2"></i> Pembayaran
                    </a>

                    <hr class="bg-light">

                    <a class="nav-link {{ request()->routeIs('Parent.ganti-password') ? 'active' : '' }}"
                        href="{{ route('Parent.ganti-password') }}">
                        <i class="fas fa-key me-2"></i> Ganti Password
                    </a>

                    <a class="nav-link" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </nav>
            </div>


            {{-- Main Content --}}
            <div class="col-md-10 p-0">
                {{-- Top Navbar --}}
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h1">@yield('title')</span>
                        <div class="ms-auto">
                            <span class="me-3">
                                <i class="fas fa-user-circle"></i> {{ auth()->user()->name }}
                            </span>
                            <span class="badge bg-primary">Orang Tua</span>
                        </div>
                    </div>
                </nav>

                {{-- Content --}}
                <div class="p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
