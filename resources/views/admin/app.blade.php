<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            width: 240px;
            background-color: #fff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            position: fixed;
            padding: 20px;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #333;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
        }
        .sidebar a.active, .sidebar a:hover {
            background-color: #0d6efd;
            color: #fff;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .card-icon {
            font-size: 2rem;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-color: #fff;
            margin-left: -40px;
            z-index: -1;
            margin-top: -20px;
            margin-right: -20px;
            height: 70px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h5 class="mb-4">Posyandu Remaja<br><strong>Cinta Sehat Desa Kuta</strong></h5>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid"></i> Dashboard
        </a>
        <a href="{{ route('admin.peserta.index') }}" class="{{ request()->routeIs('admin.peserta.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Daftar Peserta
        </a>
        <a href="{{ route('admin.petugas.index') }}" class="{{ request()->routeIs('admin.petugas.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Daftar Petugas
        </a>
        <a href="{{ route('admin.pemeriksaan.index') }}" class="{{ request()->routeIs('admin.pemeriksaan.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard-data"></i> Daftar Pemeriksaan
        </a>
        <hr>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-link p-0 text-start w-100">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </div>

    <!-- Content -->
    <div class="content">
        <nav class="navbar mb-4 px-3">
            <span class="navbar-brand mb-0 h4">@yield('title')</span>
        </nav>

        @yield('content')
    </div>

</body>
</html>
