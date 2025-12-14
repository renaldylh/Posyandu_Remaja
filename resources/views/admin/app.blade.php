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
        
        /* Sidebar */
        .sidebar {
            height: 100vh;
            width: 240px;
            background-color: #e8f5e9;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            position: fixed;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s ease;
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
            overflow-y: auto;
        }
        .sidebar-menu a {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 5px;
            border-left: 3px solid transparent;
            font-size: 0.9rem;
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
        .sidebar-footer button {
            color: #dc3545;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            background: none;
            border: none;
            width: 100%;
        }
        .sidebar-footer button:hover {
            color: #a71d2a;
        }
        .sidebar-footer button i {
            margin-right: 10px;
        }
        
        /* Content */
        .content {
            margin-left: 240px;
            padding: 20px;
            min-height: 100vh;
        }
        .card-icon {
            font-size: 2rem;
        }
        
        /* Top Navbar for Mobile */
        .mobile-navbar {
            display: none;
            background-color: #198754;
            color: #fff;
            padding: 10px 15px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1001;
        }
        .mobile-navbar .navbar-brand {
            color: #fff;
            font-weight: bold;
            font-size: 1rem;
        }
        .mobile-navbar .btn-menu {
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            padding: 0;
        }
        
        /* Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        
        /* Content Navbar */
        .content-navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            background-color: #fff;
            padding: 15px 20px;
            margin: -20px -20px 20px -20px;
            border-radius: 0;
        }
        .content-navbar h4 {
            margin: 0;
            font-size: 1.25rem;
        }
        
        /* Table Responsive */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Mobile Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .sidebar-overlay.show {
                display: block;
            }
            .content {
                margin-left: 0;
                padding-top: 70px;
            }
            .mobile-navbar {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .content-navbar {
                margin-top: 0;
            }
        }
        
        @media (max-width: 576px) {
            .content {
                padding: 60px 10px 10px 10px;
            }
            .content-navbar {
                margin: -10px -10px 15px -10px;
                padding: 12px 15px;
            }
            .content-navbar h4 {
                font-size: 1rem;
            }
            .card {
                margin-bottom: 10px;
            }
            .card-body {
                padding: 15px;
            }
            .btn {
                font-size: 0.85rem;
                padding: 6px 12px;
            }
            .table {
                font-size: 0.85rem;
            }
            .table th, .table td {
                padding: 8px 6px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Mobile Navbar -->
    <div class="mobile-navbar">
        <span class="navbar-brand">Posyandu Remaja</span>
        <button class="btn-menu" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
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
                <button type="submit">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <div class="content-navbar">
            <h4>@yield('title')</h4>
        </div>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        
        // Close sidebar when clicking a link (mobile)
        document.querySelectorAll('.sidebar-menu a').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    toggleSidebar();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
