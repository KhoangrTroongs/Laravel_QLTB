<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Quản Lý Thiết Bị') | AdminLTE</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Google Fonts: Be Vietnam Pro -->
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Be Vietnam Pro', sans-serif; background-color: #f4f6f9; color: #2d3436; }
        .main-sidebar { box-shadow: 4px 0 15px rgba(0,0,0,0.05); }
        .brand-link { border-bottom: 1px solid rgba(255,255,255,0.05) !important; padding: 1.2rem 1.5rem !important; }
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active { background-color: #4a90e2 !important; box-shadow: 0 4px 12px rgba(74,144,226,0.3) !important; }
        .nav-sidebar .nav-item { margin-bottom: 4px; }
        .nav-sidebar .nav-link { border-radius: 10px !important; margin: 0 10px; padding: 10px 15px !important; transition: all 0.3s; }
        .nav-sidebar .nav-link:hover:not(.active) { background-color: rgba(255,255,255,0.05); transform: translateX(5px); }

        .content-wrapper { background-color: #f8fafc; }
        .card { border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.03) !important; overflow: hidden; margin-bottom: 2rem; }
        .card-primary.card-outline { border-top: 4px solid #4a90e2; }
        .card-success.card-outline { border-top: 4px solid #2ecc71; }
        .card-warning.card-outline { border-top: 4px solid #f1c40f; }
        .card-danger.card-outline { border-top: 4px solid #e74c3c; }
        .card-header { background-color: #fff; border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; }
        .card-title { font-weight: 700; color: #1e293b; letter-spacing: -0.5px; }

        .table { margin-bottom: 0; }
        .table thead th { background-color: #f8fafc; color: #64748b; font-weight: 600; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px; padding: 1rem 1.5rem; border: none; }
        .table tbody td { padding: 1rem 1.5rem; vertical-align: middle; color: #334155; border-top: 1px solid #f1f5f9; }
        .table-hover tbody tr:hover { background-color: #f1f5f9; }

        .badge { padding: 0.5rem 0.75rem; border-radius: 8px; font-weight: 600; font-size: 0.75rem; }
        .btn { border-radius: 10px; font-weight: 600; padding: 0.6rem 1.2rem; transition: all 0.3s; }
        .btn-xs { padding: 0.25rem 0.5rem; border-radius: 6px; }
        .btn-success { background: #2ecc71; border-color: #2ecc71; }
        .btn-success:hover { background: #27ae60; border-color: #27ae60; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(46,204,113,0.3); }

        .small-box { border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .small-box .inner { padding: 1.5rem; }
        .small-box h3 { font-weight: 800; }

        /* Custom Input & Alignments */
        .form-control, .form-control-sm { border-radius: 10px; border: 1px solid #e2e8f0; padding: 0.6rem 1rem; transition: all 0.2s; height: auto; }
        .form-control:focus { border-color: #4a90e2; box-shadow: 0 0 0 3px rgba(74,144,226,0.1); }
        .input-group > .form-control { border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important; }
        .input-group > .input-group-append > .btn { border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important; height: 100%; }
        
        label { font-weight: 600; color: #475569; font-size: 0.85rem; margin-bottom: 0.5rem; }
        .input-group-text { border-radius: 10px; background-color: #f8fafc; border: 1px solid #e2e8f0; }
        
        /* Fix icon alignment in headers */
        .card-header .card-title i { margin-right: 10px; color: #4a90e2; vertical-align: middle; }
        .card-header .card-title { display: flex; align-items: center; }

        /* Sidebar Mini Fixes */
        .sidebar-collapse .brand-link i { margin-left: 0.8rem !important; margin-right: 0 !important; float: none !important; }
        .sidebar-collapse .brand-text { display: none; }
    </style>
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="nav-link text-muted">
                    <i class="far fa-clock mr-1"></i>{{ now()->format('d/m/Y') }}
                </span>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ url('/') }}" class="brand-link">
            <i class="fas fa-laptop-medical brand-image opacity-80 ml-3 mr-2" style="font-size: 1.5rem; line-height: 1.8rem;"></i>
            <span class="brand-text font-weight-bold" style="font-size: 1.25rem; letter-spacing: 0.5px;">QL Thiết Bị</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('equipment.index') }}" class="nav-link {{ request()->routeIs('equipment.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-laptop"></i>
                            <p>Quản Lý Thiết Bị</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Quản Lý Nhân Viên</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('equipment-users.index') }}" class="nav-link {{ request()->routeIs('equipment-users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-handshake"></i>
                            <p>Mượn Thiết Bị</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer bg-white border-top shadow-sm">
        <div class="float-right d-none d-sm-inline">
            <span class="badge badge-light border">Phiên bản 2.0.1</span>
        </div>
        <strong>Bản quyền &copy; {{ date('Y') }} <a href="#" class="text-primary">QLTB Intern Project</a>.</strong> Mọi quyền được bảo lưu.
    </footer>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>

