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
            background-color: #e8f5e9;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            position: fixed;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            background-color: #198754;
            color: #fff;
            padding: 15px 20px;
        }
        .sidebar-header h6 {
            margin: 0;
            font-size: 0.9rem;
            font-weight: normal;
        }
        .sidebar-header h5 {
            margin: 0;
            font-size: 1rem;
            font-weight: bold;
        }
        .sidebar-menu {
            flex: 1;
            padding: 15px;
        }
        .sidebar-menu a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 5px;
            border-left: 3px solid transparent;
        }
        .sidebar-menu a.active {
            background-color: #fff;
            color: #198754;
            border-left: 3px solid #198754;
            font-weight: 500;
        }
        .sidebar-menu a:hover:not(.active) {
            background-color: rgba(255,255,255,0.5);
        }
        .sidebar-menu a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .sidebar-footer {
            padding: 15px;
            border-top: 1px solid #c8e6c9;
        }
        .sidebar-footer a {
            color: #dc3545;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px 15px;
        }
        .sidebar-footer a:hover {
            color: #a71d2a;
        }
        .sidebar-footer a i {
            margin-right: 10px;
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
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h6>Posyandu Remaja</h6>
            <h5>Cinta Sehat Desa Kuta</h5>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid"></i> Dashboard
            </a>
            <a href="{{ route('admin.peserta.index') }}" class="{{ request()->routeIs('admin.peserta.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Daftar Peserta
            </a>
            <a href="{{ route('admin.petugas.index') }}" class="{{ request()->routeIs('admin.petugas.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> Daftar Petugas
            </a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Management User
            </a>
            <a href="{{ route('admin.pemeriksaan.index') }}" class="{{ request()->routeIs('admin.pemeriksaan.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data"></i> Daftar Pemeriksaan
            </a>
        </div>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn btn-link p-0 text-danger text-decoration-none d-flex align-items-center" style="padding: 10px 15px !important;">
                    <i class="bi bi-box-arrow-left me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <nav class="navbar mb-4 px-3">
            <span class="navbar-brand mb-0 h4">@yield('title')</span>
        </nav>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
