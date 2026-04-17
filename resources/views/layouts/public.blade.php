<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Thiết Bị Cho Mượn')</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Google Fonts -->
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
        :root {
            --primary-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            --accent-gradient: linear-gradient(135deg, #3b82f6, #2563eb);
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --card-hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        body { 
            font-family: 'Be Vietnam Pro', sans-serif; 
            background: #f8fafc; 
            color: #1e293b;
        }

        /* Navbar */
        .public-navbar {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .public-navbar .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            color: #fff !important;
            letter-spacing: -0.05em;
        }
        .public-navbar .nav-link { 
            color: rgba(255,255,255,0.7) !important; 
            font-weight: 500; 
            transition: all 0.3s;
            padding: 0.5rem 1rem !important;
        }
        .public-navbar .nav-link:hover { 
            color: #fff !important; 
            transform: translateY(-1px);
        }
        .public-navbar .btn-login {
            background: var(--accent-gradient);
            color: #fff !important;
            border-radius: 12px;
            padding: 0.6rem 1.5rem !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .public-navbar .btn-register {
            border: 2px solid rgba(255,255,255,0.2);
            color: #fff !important;
            border-radius: 12px;
            padding: 0.6rem 1.5rem !important;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        .public-navbar .btn-avatar {
            width: 38px; height: 38px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.2);
        }

        /* Hero Section */
        .hero-section {
            background: var(--primary-gradient);
            padding: 6rem 0 4rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            transform: translate(200px, -200px);
        }
        .hero-section h1 { font-weight: 800; font-size: 3.5rem; letter-spacing: -0.05em; margin-bottom: 1.5rem; }
        .hero-section p { color: rgba(255,255,255,0.7); font-size: 1.25rem; line-height: 1.6; }
        
        .hero-search {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 6px;
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .hero-search .form-control {
            border-radius: 14px;
            border: none;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            height: auto;
            background: #fff;
        }
        .hero-search .btn-search {
            border-radius: 14px;
            padding: 0 2rem;
            background: #3b82f6;
            margin-left: 6px;
            border: none;
            color: #fff;
            font-weight: 700;
            transition: all 0.3s;
        }
        .hero-search .btn-search:hover {
            background: #2563eb;
            transform: scale(1.02);
        }

        /* Equipment Cards */
        .equipment-card {
            border-radius: 24px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            background: #fff;
            box-shadow: var(--card-shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }
        .equipment-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-hover-shadow);
            border-color: #3b82f6;
        }
        .equipment-card .card-img-top {
            height: 200px;
            object-fit: cover;
            border-radius: 24px 24px 0 0;
        }
        .equipment-card .card-img-placeholder {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            font-size: 5rem;
            color: #cbd5e1;
            border-radius: 24px 24px 0 0;
        }
        .badge-available {
            background: #dcfce7;
            color: #15803d;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
        }

        /* Footer */
        .public-footer {
            background: #0f172a;
            color: rgba(255,255,255,0.5);
            padding: 3rem 0;
            margin-top: 5rem;
        }

        /* Unified Input Group for Password Toggle */
        .input-group-auth {
            display: flex;
            align-items: stretch;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.2s;
            background: #fff;
            overflow: hidden;
        }
        .input-group-auth:focus-within {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
        }
        .input-group-auth .form-control, .input-group-auth .form-control-auth {
            border: none !important;
            flex: 1;
            box-shadow: none !important;
        }
        .input-group-auth .btn-toggle-pwd {
            border: none !important;
            background: transparent !important;
            color: #94a3b8;
            padding: 0 1rem;
            transition: color 0.2s;
            outline: none !important;
            box-shadow: none !important;
        }
        .input-group-auth .btn-toggle-pwd:hover {
            color: #3b82f6;
        }
    </style>
    @stack('styles')
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg public-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-laptop-medical mr-2"></i>QL Thiết Bị
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span style="color:#fff; font-size:1.5rem;"><i class="fas fa-bars"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                @if(Auth::check() && Auth::user()->hasAnyRole(['admin', 'editor']))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="fas fa-home mr-1"></i> Trang Chủ
                    </a>
                </li>
                @endif
            </ul>
            <div class="navbar-nav">
                @auth
                    <!-- Notifications -->
                    <div class="nav-item dropdown mr-3 border-right pr-3">
                         <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="notifDropdown"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="position: relative; padding: 10px;">
                            <i class="far fa-bell" style="font-size: 1.5rem; color: rgba(255,255,255,0.9);"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="badge badge-danger" style="font-size: 0.6rem; position: absolute; top: 6px; right: 2px; padding: 2px 4px; border-radius: 50%; min-width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; border: 2px solid #0f172a;">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-right border-0 shadow-lg mt-3" style="border-radius: 15px; overflow: hidden; min-width: 320px;">
                            <div class="dropdown-header text-center py-3 bg-light border-bottom">
                                <span class="text-dark font-weight-bold" style="font-size: 0.95rem;">
                                    Thông báo mới ({{ auth()->user()->unreadNotifications->count() }})
                                </span>
                            </div>
                            <div style="max-height: 350px; overflow-y: auto;">
                                @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                                    @php
                                        $type = $notification->data['type'] ?? 'default';
                                        $bgClass = match($type) {
                                            'overdue_reminder' => 'danger',
                                            'new_request' => 'info',
                                            'request_response' => 'success',
                                            default => 'secondary'
                                        };
                                        $iconClass = match($type) {
                                            'overdue_reminder' => 'fa-exclamation-triangle',
                                            'new_request' => 'fa-envelope',
                                            'request_response' => 'fa-check-circle',
                                            default => 'fa-info-circle'
                                        };
                                    @endphp
                                    <form action="{{ route('profile.notifications.markAsRead', $notification->id) }}" method="POST" id="public-notif-{{ $notification->id }}">
                                        @csrf
                                        <a href="javascript:void(0)" onclick="document.getElementById('public-notif-{{ $notification->id }}').submit();" class="dropdown-item py-3 border-bottom">
                                            <div class="media align-items-center">
                                                <div class="bg-{{ $bgClass }} rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; flex-shrink: 0;">
                                                    <i class="fas {{ $iconClass }} text-white" style="font-size: 0.9rem;"></i>
                                                </div>
                                                <div class="media-body" style="white-space: normal;">
                                                    <p class="mb-0 font-weight-bold text-dark small">{{ $notification->data['title'] }}</p>
                                                    <p class="mb-0 text-muted extra-small">{{ Str::limit($notification->data['message'], 55) }}</p>
                                                    <small class="text-primary font-weight-bold">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </form>
                                @empty
                                    <div class="dropdown-item text-center py-5 text-muted">
                                        <i class="fas fa-check-circle fa-2x mb-2 opacity-25"></i>
                                        <p class="mb-0">Không có thông báo mới.</p>
                                    </div>
                                @endforelse
                            </div>
                            <a href="{{ route('profile.notifications.index') }}" class="dropdown-item dropdown-footer font-weight-bold py-2 bg-light text-center text-primary">Xem tất cả</a>
                        </div>
                    </div>

                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if(Auth::user()->avatar)
                                @php
                                    $userAvatarUrl = str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar);
                                @endphp
                                <img src="{{ $userAvatarUrl }}"
                                     class="btn-avatar mr-2 shadow-sm" alt="Avatar">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff"
                                     class="btn-avatar mr-2 shadow-sm" alt="Avatar">
                            @endif
                            <span class="text-white font-weight-bold">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="fas fa-user-circle mr-2 text-primary"></i>Hồ sơ cá nhân
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-link btn-login mr-2">
                        <i class="fas fa-sign-in-alt mr-1"></i> Đăng Nhập
                    </a>
                    <a href="{{ route('register') }}" class="nav-link btn-register">
                        <i class="fas fa-user-plus mr-1"></i> Đăng Ký
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

@yield('content')

<!-- Footer -->
<footer class="public-footer">
    <div class="container text-center">
        <strong style="color: rgba(255,255,255,0.8);">QL Thiết Bị</strong> &copy; {{ date('Y') }}.
        Hệ thống quản lý mượn thiết bị nội bộ.
    </div>
</footer>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(function() {
        const Toast = Swal.mixin({
            toast: true, position: 'top-end',
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
    });

    // Real-time Notifications with Echo
    @auth
        if (typeof window.Echo !== 'undefined') {
            window.Echo.private(`App.Models.User.{{ auth()->id() }}`)
                .notification((notification) => {
                    console.log('New notification:', notification);
                    
                    // Update badge count
                    const badge = $('#notifDropdown .badge');
                    let currentCount = parseInt(badge.text() || 0);
                    currentCount++;
                    
                    if (badge.length) {
                        badge.text(currentCount).show();
                    } else {
                        $('#notifDropdown').append(`<span class="badge badge-danger" style="font-size: 0.6rem; position: absolute; top: 6px; right: 2px; padding: 2px 4px; border-radius: 50%; min-width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; border: 2px solid #0f172a;">${currentCount}</span>`);
                    }

                    // Show Toast
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true
                    });

                    Toast.fire({
                        icon: notification.type === 'overdue_reminder' ? 'warning' : 'info',
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
