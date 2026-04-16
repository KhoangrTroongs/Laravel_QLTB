@extends('layouts.auth')
@section('title', 'Đăng Nhập')

@section('content')
<div class="auth-card card">
    <div class="card-header">
        <div class="auth-brand-icon"><i class="fas fa-laptop-medical"></i></div>
        <h1 class="auth-brand-title">QL Thiết Bị</h1>
        <p class="auth-brand-sub">Đăng nhập vào hệ thống</p>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger rounded-xl mb-4">
                <i class="fas fa-exclamation-circle mr-1"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="font-weight-bold text-muted small">EMAIL</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control form-control-auth @error('email') is-invalid @enderror"
                       placeholder="your@email.com" autofocus required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="font-weight-bold text-muted small">MẬT KHẨU</label>
                <div class="input-group" style="background: none; border: none;">
                    <input type="password" name="password" id="password"
                           class="form-control form-control-auth @error('password') is-invalid @enderror"
                           placeholder="••••••••" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" id="togglePwd"
                                style="border-radius: 0 12px 12px 0; border-left: none;"
                                onclick="togglePassword()">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group d-flex justify-content-between align-items-center">
                <label class="d-flex align-items-center mb-0" style="cursor: pointer;">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-muted small">Ghi nhớ đăng nhập</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary btn-auth mb-3">
                <i class="fas fa-sign-in-alt mr-2"></i> Đăng Nhập
            </button>
        </form>

        <div class="auth-divider">hoặc</div>

        <div class="text-center mt-3">
            <span class="text-muted small">Chưa có tài khoản?</span>
            <a href="{{ route('register') }}" class="font-weight-bold ml-1">Đăng ký ngay</a>
        </div>
        <div class="text-center mt-2">
            <a href="{{ route('home') }}" class="text-muted small">
                <i class="fas fa-arrow-left mr-1"></i>Về trang chủ
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        pwd.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
</script>
@endpush
@endsection
