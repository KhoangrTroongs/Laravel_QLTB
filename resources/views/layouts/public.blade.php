<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Thiết Bị Cho Mượn') | AdminLTE 4</title>

    <!-- Fontsource (Source Sans 3) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <!-- Font Awesome -->
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
    </script>
    @vite(['resources/js/app.js'])
    
    <style>
        /* Application specific styling for the public landing page items */
        .equipment-card {
            height: 100%;
            transition: all 0.3s;
        }
        .equipment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .equipment-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .equipment-card .card-img-placeholder {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            font-size: 5rem;
            color: #dee2e6;
        }
    </style>
    @stack('styles')
</head>
<body class="layout-top-nav bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Navbar -->
        <nav class="app-header navbar navbar-expand-md bg-body shadow-sm">
            <div class="container">
                <a href="{{ route('home') }}" class="navbar-brand">
                    <span class="brand-text fw-light"><i class="bi bi-laptop"></i> QL Thiết Bị</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        @if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'editor']))
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('home') }}" class="nav-link">Trang chủ</a>
                            </li>
                        @endif
                    </ul>

                    <!-- Right navbar links -->
                    <ul class="navbar-nav ms-auto">
                        @auth
                            <!-- Notifications Dropdown -->
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
                                            <form action="{{ route('profile.notifications.markAsRead', $notification->id) }}" method="POST" id="public-notif-{{ $notification->id }}">
                                                @csrf
                                                <a href="javascript:void(0)" onclick="document.getElementById('public-notif-{{ $notification->id }}').submit();" class="dropdown-item">
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

                            <!-- User Menu -->
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
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">Đăng Nhập</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('register') }}" class="nav-link">Đăng Ký</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="app-main">
            <div class="app-content pt-4">
                <div class="container">
                    @yield('content')
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="app-footer">
            <div class="container text-center">
                <div class="float-end d-none d-sm-inline">Phiên bản 2.0.1</div>
                <strong>Bản quyền &copy; {{ date('Y') }} QLTB Intern Project.</strong> Mọi quyền được bảo lưu.
            </div>
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
        $(function() {
            const Toast = Swal.mixin({
                toast: true, position: 'top-end',
                showConfirmButton: false, timer: 5000, timerProgressBar: true,
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

                        const dropdownItems = $('#notification-dropdown-items');
                        if (dropdownItems.length) {
                            dropdownItems.find('.text-muted').parent().remove(); // Remove "No notification"
                            
                            const notifHtml = `
                                <form action="/profile/notifications/${notification.id}/read" method="POST" id="public-notif-${notification.id}">
                                    <input type="hidden" name="_token" value="${window.Laravel.csrfToken}">
                                    <a href="javascript:void(0)" onclick="document.getElementById('public-notif-${notification.id}').submit();" class="dropdown-item">
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
    </script>
    @stack('scripts')
</body>
</html>
