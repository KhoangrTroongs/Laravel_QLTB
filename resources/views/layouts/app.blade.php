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
    <!-- Compiled Assets -->
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            userId: {{ auth()->check() ? auth()->id() : 'null' }}
        };
    </script>
    @vite(['resources/js/app.js'])
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
        .btn { border-radius: 10px; font-weight: 600; padding: 0.6rem 1.25rem; transition: all 0.2s; letter-spacing: 0.025em; }
        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.8rem; border-radius: 8px; }
        .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.75rem; border-radius: 6px; }
        .btn-primary { background: #2563eb; border: none; box-shadow: 0 4px 12px rgba(37,99,235,0.15); }
        .btn-primary:hover { background: #1d4ed8; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37,99,235,0.3); }

        .badge { padding: 0.35rem 0.7rem; border-radius: 8px; font-weight: 600; font-size: 0.75rem; letter-spacing: 0.025em; }
        .badge-pill { border-radius: 50rem; padding-left: 1rem; padding-right: 1rem; }
        
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
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false" title="Thông báo" style="padding: 10px 15px; display: flex; align-items: center; position: relative;">
                    <i class="far fa-bell" style="font-size: 1.5rem;"></i>
                    @php $unreadNotifCount = auth()->user()->unreadNotifications->count(); @endphp
                    <span id="nav-notif-badge" class="badge badge-danger navbar-badge {{ $unreadNotifCount == 0 ? 'd-none' : '' }}" style="font-size: 0.65rem; top: 4px; right: 6px; padding: 2px 4px; border-radius: 50%; min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border: 2px solid #fff;">
                        {{ $unreadNotifCount }}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right border-0 shadow-lg mt-2" style="border-radius: 15px; overflow: hidden; min-width: 320px;">
                    <div class="dropdown-header text-center py-3 bg-light border-bottom">
                        <span class="text-dark font-weight-bold" style="font-size: 1rem;">
                            <i class="fas fa-bell mr-2 text-primary"></i>Thông báo (<span id="notif-count-val">{{ $unreadNotifCount }}</span>)
                        </span>
                    </div>
                    <div class="dropdown-divider m-0"></div>
                    <div id="notification-dropdown-items" style="max-height: 350px; overflow-y: auto;">
                        @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                            @php
                                $type = $notification->data['type'] ?? 'default';
                                $bgClass = match($type) {
                                    'overdue_reminder' => 'danger',
                                    'new_request' => 'info',
                                    'request_response' => 'success',
                                    'missed_request' => 'warning',
                                    default => 'secondary'
                                };
                                $iconClass = match($type) {
                                    'overdue_reminder' => 'fa-exclamation-triangle',
                                    'new_request' => 'fa-envelope',
                                    'request_response' => 'fa-check-circle',
                                    'missed_request' => 'fa-history',
                                    default => 'fa-info-circle'
                                };
                            @endphp
                            <form action="{{ route('profile.notifications.markAsRead', $notification->id) }}" method="POST" id="read-notif-{{ $notification->id }}">
                                @csrf
                                <a href="javascript:void(0)" onclick="document.getElementById('read-notif-{{ $notification->id }}').submit();" class="dropdown-item py-3 border-bottom h-100 transition-all hover-bg-light">
                                    <div class="media align-items-center">
                                        <div class="bg-{{ $bgClass }} rounded-circle shadow-sm mr-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0;">
                                            <i class="fas {{ $iconClass }} text-white"></i>
                                        </div>
                                        <div class="media-body">
                                            <p class="mb-0 text-dark font-weight-bold small" style="line-height: 1.2;">{{ $notification->data['title'] }}</p>
                                            <p class="mb-1 text-muted small mt-1" style="line-height: 1.3;">{{ Str::limit($notification->data['message'], 65) }}</p>
                                            <span class="text-xs text-primary font-weight-600">
                                                <i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </form>
                        @empty
                            <div class="dropdown-item text-center py-5 text-muted">
                                <i class="fas fa-check-double fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0 font-weight-bold">Không có thông báo mới</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="dropdown-divider m-0"></div>
                    <a href="{{ route('profile.notifications.index') }}" class="dropdown-item dropdown-footer text-center py-2 bg-light font-weight-bold text-primary">
                        Xem tất cả thông báo <i class="fas fa-chevron-right ml-1 small"></i>
                    </a>
                </div>
            </li>

            <li class="nav-item">
                <a href="{{ route('home') }}" class="nav-link" title="Trang chủ" style="padding: 10px 15px;">
                    <i class="fas fa-home" style="font-size: 1.4rem;"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center" href="#" id="navbarDropdown"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(Auth::user()->avatar)
                        @php
                            $userAvatarUrl = str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar);
                        @endphp
                        <img src="{{ $userAvatarUrl }}"
                             class="rounded-circle mr-2 border shadow-xs"
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
                    @if(Auth::user()->hasAnyRole(['admin', 'editor']))
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
                        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Loại Thiết Bị</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('equipment-users.index') }}" class="nav-link {{ request()->routeIs('equipment-users.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-handshake text-info"></i>
                            <p>Phân Phát Thiết Bị</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('equipment-users.queue') }}" class="nav-link {{ request()->routeIs('equipment-users.queue') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hourglass-start text-warning"></i>
                            <p>
                                Hàng Đợi Duyệt
                                @php $pendingCount = \App\Models\EquipmentUser::where('status', 0)->count(); @endphp
                                <span id="sidebar-queue-badge" class="badge badge-warning right shadow-xs {{ $pendingCount == 0 ? 'd-none' : '' }}">
                                    {{ $pendingCount }}
                                </span>
                            </p>
                        </a>
                    </li>
                    @endif
                    
                    <li class="nav-item">
                        <a href="{{ route('profile.notifications.index') }}" class="nav-link {{ request()->routeIs('profile.notifications.index') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bell"></i>
                            <p>Thông Báo</p>
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
                            <li class="breadcrumb-item">
                                @if(Auth::user()->hasAnyRole(['admin', 'editor']))
                                    <a href="{{ route('dashboard') }}">Home</a>
                                @else
                                    <a href="{{ route('home') }}">Home</a>
                                @endif
                            </li>
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

    // Real-time Notifications with Echo
    @auth
        if (typeof window.Echo !== 'undefined') {
            window.Echo.private(`App.Models.User.{{ auth()->id() }}`)
                .notification((notification) => {
                    console.log('New notification:', notification);
                    
                    // 1. Update Navbar Badge (Bell)
                    const navBadge = $('#nav-notif-badge');
                    let currentNavCount = parseInt(navBadge.text().trim() || 0);
                    navBadge.text(currentNavCount + 1).removeClass('d-none').show();

                    // 2. Update Dropdown Header Count
                    const headerVal = $('#notif-count-val');
                    if (headerVal.length) {
                        headerVal.text(currentNavCount + 1);
                    }

                    // 3. Update Sidebar Badge (if new request)
                    if (notification.type.includes('NewBorrowRequest')) {
                        const sidebarBadge = $('#sidebar-queue-badge');
                        if (sidebarBadge.length) {
                            let count = parseInt(sidebarBadge.text().trim() || 0);
                            sidebarBadge.text(count + 1).removeClass('d-none').show();
                        }
                    }

                    // 4. Prepend to Dropdown Items
                    const dropdownItems = $('#notification-dropdown-items');
                    if (dropdownItems.length) {
                        // Remove "No notification" message if present
                        dropdownItems.find('.opacity-25').closest('div').remove();

                        const bgMapping = {
                            'OverdueReminderNotification': 'danger',
                            'NewBorrowRequest': 'info',
                            'BorrowRequestResponse': 'success',
                            'MissedBorrowRequest': 'warning'
                        };
                        const iconMapping = {
                            'OverdueReminderNotification': 'fa-exclamation-triangle',
                            'NewBorrowRequest': 'fa-envelope',
                            'BorrowRequestResponse': 'fa-check-circle',
                            'MissedBorrowRequest': 'fa-history'
                        };

                        let bgClass = 'secondary';
                        let iconClass = 'fa-info-circle';
                        
                        Object.keys(bgMapping).forEach(key => {
                            if (notification.type.includes(key)) {
                                bgClass = bgMapping[key];
                                iconClass = iconMapping[key];
                            }
                        });

                        const notifHtml = `
                            <form action="/profile/notifications/${notification.id}/read" method="POST" id="read-notif-${notification.id}">
                                <input type="hidden" name="_token" value="${Laravel.csrfToken}">
                                <a href="javascript:void(0)" onclick="document.getElementById('read-notif-${notification.id}').submit();" class="dropdown-item py-3 border-bottom h-100 transition-all hover-bg-light animate__animated animate__fadeInDown">
                                    <div class="media align-items-center">
                                        <div class="bg-${bgClass} rounded-circle shadow-sm mr-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0;">
                                            <i class="fas ${iconClass} text-white"></i>
                                        </div>
                                        <div class="media-body">
                                            <p class="mb-0 text-dark font-weight-bold small" style="line-height: 1.2;">${notification.title}</p>
                                            <p class="mb-1 text-muted small mt-1" style="line-height: 1.3;">${notification.message}</p>
                                            <span class="text-xs text-primary font-weight-600">
                                                <i class="far fa-clock mr-1"></i>vừa xong
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </form>
                        `;
                        dropdownItems.prepend(notifHtml);
                    }

                    // 5. Show Toast
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true
                    });

                    Toast.fire({
                        icon: notification.type.includes('Overdue') ? 'warning' : 'info',
                        title: notification.title,
                        text: notification.message
                    });
                });
            
            window.Echo.channel('test-channel')
                .listen('TestBroadcast', (e) => {
                    console.log('Test Broadcast received:', e.message);
                });
        }
    @endauth
</script>
</body>
</html>

