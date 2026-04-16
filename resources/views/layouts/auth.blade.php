<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Quản Lý Thiết Bị')</title>
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
    <style>
        body { font-family: 'Be Vietnam Pro', sans-serif; background: #f1f5f9; }

        /* Auth card centering */
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
        .auth-card {
            width: 100%;
            max-width: 460px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        }
        .auth-card .card-header {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            padding: 2.5rem 2rem;
            text-align: center;
            border: none;
        }
        .auth-card .card-body { padding: 2.5rem 2rem; background: #fff; }
        .auth-brand-icon { font-size: 3rem; color: #fff; margin-bottom: 1rem; }
        .auth-brand-title { color: #fff; font-size: 1.6rem; font-weight: 800; margin: 0; }
        .auth-brand-sub { color: rgba(255,255,255,0.8); font-size: 0.9rem; margin-top: 0.25rem; }

        .form-control-auth {
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            padding: 0.85rem 1.25rem;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        .form-control-auth:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
        }
        .btn-auth {
            width: 100%;
            padding: 0.9rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.025em;
        }
        .auth-divider { text-align: center; color: #94a3b8; margin: 1.5rem 0; position: relative; }
        .auth-divider::before, .auth-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 42%;
            height: 1px;
            background: #e2e8f0;
        }
        .auth-divider::before { left: 0; }
        .auth-divider::after { right: 0; }
    </style>
</head>
<body class="hold-transition">
<div class="auth-wrapper">
    @yield('content')
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
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
</script>
</body>
</html>
