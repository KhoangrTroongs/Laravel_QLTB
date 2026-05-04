<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Quản Lý Thiết Bị') | AdminLTE 4</title>

    <!-- Fontsource (Source Sans 3) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <!-- Font Awesome (for compatibility with existing views) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- AdminLTE 4 CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            userId: {{ auth()->check() ? auth()->id() : 'null' }}
        };
        
        // Initialize Dark Mode from localStorage to avoid FOUC
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>
    @vite(['resources/js/app.js'])
    @stack('styles')
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-mini bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Navbar -->
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <!-- Start Navbar Links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="{{ Auth::check() && Auth::user()->hasAnyRole(['admin', 'editor']) ? route('dashboard') : route('home') }}" class="nav-link">Trang chủ</a>
                    </li>
                </ul>

                <!-- End Navbar Links -->
                <ul class="navbar-nav ms-auto">
                    <!-- Notifications Dropdown Menu -->
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-bell-fill"></i>
                            @php $unreadNotifCount = auth()->user()->unreadNotifications->count(); @endphp
                            <span id="nav-notif-badge" class="navbar-badge badge text-bg-danger {{ $unreadNotifCount == 0 ? 'd-none' : '' }}">
                                {{ $unreadNotifCount }}
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <span class="dropdown-item dropdown-header"><span id="notif-count-val">{{ $unreadNotifCount }}</span> Thông báo</span>
                            <div class="dropdown-divider"></div>
                            <div id="notification-dropdown-items" style="max-height: 300px; overflow-y: auto;">
                                @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                                    <form action="{{ route('profile.notifications.markAsRead', $notification->id) }}" method="POST" id="read-notif-{{ $notification->id }}">
                                        @csrf
                                        <a href="javascript:void(0)" onclick="document.getElementById('read-notif-{{ $notification->id }}').submit();" class="dropdown-item">
                                            <div class="d-flex">
                                                <div class="flex-grow-1">
                                                    <h3 class="dropdown-item-title text-wrap" style="font-size: 0.9rem;">
                                                        {{ $notification->data['title'] }}
                                                    </h3>
                                                    <p class="fs-7 text-wrap">{{ Str::limit($notification->data['message'], 50) }}</p>
                                                    <p class="fs-7 text-secondary">
                                                        <i class="bi bi-clock-fill me-1"></i> {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </form>
                                    <div class="dropdown-divider"></div>
                                @empty
                                    <div class="dropdown-item text-center text-muted">
                                        Không có thông báo mới
                                    </div>
                                    <div class="dropdown-divider"></div>
                                @endforelse
                            </div>
                            <a href="{{ route('profile.notifications.index') }}" class="dropdown-item dropdown-footer">Xem tất cả thông báo</a>
                        </div>
                    </li>

                    <!-- Theme Toggle -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="darkmode">
                            <i data-lte-icon="moon" class="bi bi-moon-fill"></i>
                            <i data-lte-icon="sun" class="bi bi-sun-fill" style="display: none;"></i>
                        </a>
                    </li>

                    <!-- Fullscreen Toggle -->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i>
                        </a>
                    </li>

                    <!-- User Menu Dropdown -->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            @php
                                $userAvatarUrl = Auth::user()->avatar ? (str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar)) : "https://ui-avatars.com/api/?name=" . urlencode(Auth::user()->name) . "&background=0D8ABC&color=fff";
                            @endphp
                            <img src="{{ $userAvatarUrl }}" class="user-image rounded-circle shadow" alt="User Image">
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary">
                                <img src="{{ $userAvatarUrl }}" class="rounded-circle shadow" alt="User Image">
                                <p>
                                    {{ Auth::user()->name }}
                                    <small>Vai trò: 
                                        @foreach(Auth::user()->roles as $role)
                                            {{ $role->display_name }}@if(!$loop->last), @endif
                                        @endforeach
                                    </small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <a href="{{ route('profile.show') }}" class="btn btn-default btn-flat">Hồ Sơ</a>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline float-end">
                                    @csrf
                                    <button type="submit" class="btn btn-default btn-flat">Đăng Xuất</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endauth
                </ul>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div class="sidebar-brand">
                <a href="{{ url('/') }}" class="brand-link">
                    <i class="fas fa-laptop-medical brand-image opacity-75 shadow mt-1"></i>
                    <span class="brand-text fw-light">QL Thiết Bị</span>
                </a>
            </div>
            
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" data-accordion="false">
                        @auth
                        @if(Auth::user()->hasAnyRole(['admin', 'editor']))
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('equipment.index') }}" class="nav-link {{ request()->routeIs('equipment.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-laptop"></i>
                                <p>Quản Lý Thiết Bị</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-tags"></i>
                                <p>Loại Thiết Bị</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('equipment-users.index') }}" class="nav-link {{ request()->routeIs('equipment-users.index') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-arrow-left-right text-info"></i>
                                <p>Phân Phát Thiết Bị</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('equipment-users.queue') }}" class="nav-link {{ request()->routeIs('equipment-users.queue') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-hourglass-split text-warning"></i>
                                <p>
                                    Hàng Đợi Duyệt
                                    @php $pendingCount = \App\Models\EquipmentUser::where('status', 0)->count(); @endphp
                                    <span id="sidebar-queue-badge" class="nav-badge badge text-bg-warning {{ $pendingCount == 0 ? 'd-none' : '' }}">
                                        {{ $pendingCount }}
                                    </span>
                                </p>
                            </a>
                        </li>
                        @endif
                        
                        <li class="nav-item">
                            <a href="{{ route('profile.notifications.index') }}" class="nav-link {{ request()->routeIs('profile.notifications.index') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-bell"></i>
                                <p>Thông Báo</p>
                            </a>
                        </li>
                        
                        @if(Auth::user()->isAdmin())
                        <li class="nav-header">QUẢN TRỊ VIÊN</li>
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-people"></i>
                                <p>Quản Lý Nhân Viên</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-shield-lock"></i>
                                <p>Phân Quyền</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('trash.index') }}" class="nav-link {{ request()->routeIs('trash.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-trash text-danger"></i>
                                <p>Thùng rác</p>
                            </a>
                        </li>
                        @endif
                        @endauth
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <h3 class="mb-0">@yield('page-title', 'Dashboard')</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item">
                                    @if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'editor']))
                                        <a href="{{ route('dashboard') }}">Trang chủ</a>
                                    @else
                                        <a href="{{ route('home') }}">Trang chủ</a>
                                    @endif
                                </li>
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">Phiên bản 2.0.1</div>
            <strong>Bản quyền &copy; {{ date('Y') }} QLTB Intern Project.</strong> Mọi quyền được bảo lưu.
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- OverlayScrollbars -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <!-- Bootstrap 5 Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- AdminLTE 4 JS -->
    <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
    
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true,
        };
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined") {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>

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
                Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
            @endif
            @if(session('error'))
                Toast.fire({ icon: 'error', title: '{{ session('error') }}' });
            @endif
            @if(session('warning'))
                Toast.fire({ icon: 'warning', title: '{{ session('warning') }}' });
            @endif
            @if(session('info'))
                Toast.fire({ icon: 'info', title: '{{ session('info') }}' });
            @endif

            $(document).on('submit', '.delete-form', function(e) {
                e.preventDefault();
                const form = this;
                Swal.fire({
                    title: 'Xác nhận xóa?',
                    text: "Dữ liệu bị xóa sẽ không thể khôi phục lại!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Đồng ý xóa',
                    cancelButtonText: 'Hủy bỏ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            $(document).on('click', '.confirm-delete', function(e) {
                e.preventDefault();
                const form = $(this).btn-closest('form');
                Swal.fire({
                    title: 'Bạn có chắc chắn?',
                    text: "Dữ liệu này sẽ bị xóa vĩnh viễn và không thể khôi phục!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Có, xóa ngay!',
                    cancelButtonText: 'Hủy bỏ'
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
                        const navBadge = $('#nav-notif-badge');
                        let currentNavCount = parseInt(navBadge.text().trim() || 0);
                        navBadge.text(currentNavCount + 1).removeClass('d-none').show();

                        const headerVal = $('#notif-count-val');
                        if (headerVal.length) {
                            headerVal.text(currentNavCount + 1);
                        }

                        if (notification.type.includes('NewBorrowRequest')) {
                            const sidebarBadge = $('#sidebar-queue-badge');
                            if (sidebarBadge.length) {
                                let count = parseInt(sidebarBadge.text().trim() || 0);
                                sidebarBadge.text(count + 1).removeClass('d-none').show();
                            }
                        }

                        const dropdownItems = $('#notification-dropdown-items');
                        if (dropdownItems.length) {
                            dropdownItems.find('.text-muted').parent().remove(); // Remove "No notification"
                            
                            const notifHtml = `
                                <form action="/profile/notifications/${notification.id}/read" method="POST" id="read-notif-${notification.id}">
                                    <input type="hidden" name="_token" value="${window.Laravel.csrfToken}">
                                    <a href="javascript:void(0)" onclick="document.getElementById('read-notif-${notification.id}').submit();" class="dropdown-item">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <h3 class="dropdown-item-title text-wrap" style="font-size: 0.9rem;">
                                                    ${notification.title}
                                                </h3>
                                                <p class="fs-7 text-wrap">${notification.message}</p>
                                                <p class="fs-7 text-secondary">
                                                    <i class="bi bi-clock-fill me-1"></i> vừa xong
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </form>
                                <div class="dropdown-divider"></div>
                            `;
                            dropdownItems.prepend(notifHtml);
                        }

                        const Toast = Swal.mixin({
                            toast: true, position: 'top-end', showConfirmButton: false, timer: 5000, timerProgressBar: true
                        });
                        Toast.fire({
                            icon: notification.type.includes('Overdue') ? 'warning' : 'info',
                            title: notification.title,
                            text: notification.message
                        });
                    });
            }
        @endauth

        // Dark Mode Toggle Logic
        const toggleBtn = document.querySelector('[data-lte-toggle="darkmode"]');
        if (toggleBtn) {
            const moonIcon = toggleBtn.querySelector('.bi-moon-fill');
            const sunIcon = toggleBtn.querySelector('.bi-sun-fill');
            
            // Set initial icon based on localStorage (set in <head>)
            if (document.documentElement.getAttribute('data-bs-theme') === 'dark') {
                moonIcon.style.display = 'none';
                sunIcon.style.display = 'inline-block';
            }

            toggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const html = document.documentElement;
                const isDark = html.getAttribute('data-bs-theme') === 'dark';
                
                if (isDark) {
                    html.setAttribute('data-bs-theme', 'light');
                    localStorage.setItem('theme', 'light');
                    moonIcon.style.display = 'inline-block';
                    sunIcon.style.display = 'none';
                } else {
                    html.setAttribute('data-bs-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    moonIcon.style.display = 'none';
                    sunIcon.style.display = 'inline-block';
                }
            });
        }
    </script>
</body>
</html>
