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
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Be Vietnam Pro', sans-serif; background-color: #f1f5f9; color: #1e293b; overflow-x: hidden; }
        
        /* Premium Background & Sidebar */
        .main-sidebar { background: #1e293b !important; box-shadow: 10px 0 30px rgba(0,0,0,0.05); }
        .brand-link { border-bottom: 1px solid rgba(255,255,255,0.05) !important; padding: 1.5rem !important; background: #0f172a !important; }
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active { 
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important; 
            box-shadow: 0 10px 20px rgba(37,99,235,0.3) !important; 
            color: #fff !important;
            font-weight: 600;
        }
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active i {
            color: #fff !important;
        }
        .nav-sidebar .nav-item { margin-bottom: 8px; }
        .nav-sidebar .nav-link { border-radius: 0 !important; margin: 0; padding: 12px 20px !important; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); color: #94a3b8 !important; }
        .nav-sidebar .nav-link:hover:not(.active) { background-color: rgba(255,255,255,0.05) !important; color: #fff !important; transform: translateX(5px); }
        .nav-sidebar .nav-icon { font-size: 1.1rem; margin-right: 12px !important; }

        /* Modern Content Wrapper */
        .content-wrapper { background-color: #f8fafc; }
        .card { border-radius: 20px; border: none; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03) !important; transition: transform 0.3s, box-shadow 0.3s; margin-bottom: 2rem; }
        .card:hover { transform: translateY(-3px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05) !important; }
        
        .card-header { background-color: #fff; border-bottom: 1px solid #f1f5f9; padding: 1.5rem 2rem; border-radius: 20px 20px 0 0 !important; }
        .card-title { font-weight: 800; color: #0f172a; letter-spacing: -0.025em; font-size: 1.1rem; }
        .card-body { padding: 2rem; }
        
        /* Fix for transparent card headers in colored cards */
        .card[class*="bg-"] .card-header { background: transparent !important; border-bottom: none; }
        .card[class*="bg-"] .card-title { color: #fff !important; }
        
        /* Enhanced Tables */
        .table thead th { background-color: #f8fafc; color: #475569; font-weight: 700; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; padding: 1.25rem 1.5rem; border-bottom: 2px solid #f1f5f9; }
        .table tbody td { padding: 1.25rem 1.5rem; vertical-align: middle; color: #334155; border-top: 1px solid #f1f5f9; font-size: 0.95rem; }
        
        /* Buttons & Badges */
        .btn { border-radius: 12px; font-weight: 600; padding: 0.75rem 1.5rem; transition: all 0.2s; letter-spacing: 0.025em; }
        .btn-sm { padding: 0.5rem 1rem; font-size: 0.875rem; }
        .btn-primary { background: #2563eb; border: none; box-shadow: 0 4px 12px rgba(37,99,235,0.2); }
        .btn-primary:hover { background: #1d4ed8; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37,99,235,0.3); }
        
        .btn-input { height: calc(1.5em + 1.5rem + 2px); display: flex !important; align-items: center; justify-content: center; padding-top: 0 !important; padding-bottom: 0 !important; }

        .badge { padding: 0.5rem 0.85rem; border-radius: 10px; font-weight: 700; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.025em; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* Forms & Inputs */
        .form-control { 
            border-radius: 12px; 
            border: 1px solid #e2e8f0; 
            padding: 0.75rem 1.25rem; 
            height: calc(1.5em + 1.5rem + 2px); 
            transition: all 0.2s; 
            font-size: 0.95rem;
        }
        .form-control:focus { 
            border-color: #3b82f6; 
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); 
        }

        /* Fix Input Group */
        .input-group { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; }
        .input-group > .form-control { border: none !important; border-radius: 0 !important; height: auto !important; }
        .input-group > .input-group-append > .btn { 
            border-radius: 0 !important; 
            margin: 0 !important; 
            height: 100% !important; 
            display: flex !important;
            align-items: center !important;
            padding: 0 1.5rem !important;
        }
        .input-group:focus-within { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }

        /* Animations */
        .fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Small Box Overrides */
        .small-box { border-radius: 24px; border: none; overflow: hidden; }
        .small-box .inner { padding: 2rem; }
        .small-box h3 { font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem; }
        .small-box p { font-weight: 600; font-size: 1rem; opacity: 0.9; }
        .small-box .icon { top: 15px; right: 20px; }
        .small-box .icon > i { font-size: 70px; }

        /* Fix for sidebar collapse icons */
        .sidebar-mini.sidebar-collapse .main-sidebar:not(:hover) .nav-sidebar .nav-link {
            margin: 0 !important;
            padding: 12px 0 !important;
            display: flex !important;
            justify-content: center !important;
            border-radius: 0 !important;
        }
        .sidebar-mini.sidebar-collapse .main-sidebar:not(:hover) .nav-sidebar .nav-link p {
            display: none !important;
        }
        .sidebar-mini.sidebar-collapse .main-sidebar:not(:hover) .nav-sidebar .nav-icon {
            margin: 0 !important;
            font-size: 1.25rem;
        }
        
        /* Brand centering when collapsed */
        .sidebar-mini.sidebar-collapse .main-sidebar:not(:hover) .brand-link {
            padding: 1.5rem 0 !important;
            display: flex !important;
            justify-content: center !important;
        }
        .sidebar-mini.sidebar-collapse .main-sidebar:not(:hover) .brand-link i {
            margin: 0 !important;
            float: none !important;
        }
        .sidebar-mini.sidebar-collapse .main-sidebar:not(:hover) .brand-text {
            display: none !important;
        }

        /* Hover expansion fixes */
        .sidebar-mini.sidebar-collapse .main-sidebar:hover {
            width: 250px !important;
        }
        .sidebar-mini.sidebar-collapse .main-sidebar:hover .nav-link {
            display: flex !important;
            justify-content: flex-start !important;
            padding: 12px 20px !important;
            margin: 0 !important;
            border-radius: 0 !important;
        }
        .sidebar-mini.sidebar-collapse .main-sidebar:hover .nav-icon {
            margin-right: 12px !important;
        }
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
            @auth
            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link" title="Trang chủ">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center" href="#" id="navbarDropdown"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                             class="rounded-circle mr-2 border"
                             style="width: 32px; height: 32px; object-fit: cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff"
                             class="rounded-circle mr-2"
                             style="width: 32px; height: 32px;">
                    @endif
                    <span class="text-dark font-weight-bold" style="font-size: 0.9rem;">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down ml-2 text-muted" style="font-size: 0.7rem;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow-sm" style="border-radius: 12px; border: none; min-width: 200px;">
                    <div class="dropdown-item-text py-2 px-3">
                        <small class="text-muted">Đăng nhập với vai trò</small>
                        <div>
                            @foreach(Auth::user()->roles as $role)
                                <span class="badge {{ $role->name === 'admin' ? 'badge-danger' : ($role->name === 'editor' ? 'badge-primary' : 'badge-secondary') }}"
                                      style="font-size: 0.7rem; border-radius: 6px;">
                                    {{ $role->display_name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                        <i class="fas fa-user-circle mr-2 text-primary"></i> Hồ Sơ Cá Nhân
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt mr-2"></i> Đăng Xuất
                        </button>
                    </form>
                </div>
            </li>
            @endauth
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
                        <a href="{{ route('equipment-users.index') }}" class="nav-link {{ request()->routeIs('equipment-users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-handshake"></i>
                            <p>Mượn Thiết Bị</p>
                        </a>
                    </li>
                    @auth
                    @if(Auth::user()->isAdmin())
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Quản Lý Nhân Viên</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shield-alt"></i>
                            <p>Phân Quyền</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('trash.index') }}" class="nav-link {{ request()->routeIs('trash.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-trash-alt text-danger"></i>
                            <p>Thùng rác</p>
                        </a>
                    </li>
                    @endif
                    @endauth
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
<script>
    $(function() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            showCloseButton: true,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        @if(session('warning'))
            Toast.fire({
                icon: 'warning',
                title: '{{ session('warning') }}'
            });
        @endif

        @if(session('info'))
            Toast.fire({
                icon: 'info',
                title: '{{ session('info') }}'
            });
        @endif

        // Global Delete Confirmation
        $(document).on('submit', '.delete-form', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Xác nhận xóa?',
                text: "Dữ liệu bị xóa sẽ không thể khôi phục lại!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Đồng ý xóa',
                cancelButtonText: 'Hủy bỏ',
                reverseButtons: true,
                borderRadius: '16px'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
        // Confirm delete with SweetAlert2
        $(document).on('click', '.confirm-delete', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: "Dữ liệu này sẽ bị xóa vĩnh viễn và không thể khôi phục!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Có, xóa ngay!',
                cancelButtonText: 'Hủy bỏ',
                reverseButtons: true,
                borderRadius: '15px'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
</body>
</html>

