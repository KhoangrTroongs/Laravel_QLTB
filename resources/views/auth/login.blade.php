@extends('layouts.auth')
@section('title', 'Đăng Nhập')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Đăng nhập vào hệ thống</p>

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-1"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input-group mb-3">
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="Email" autofocus required>
                <div class="input-group-text">
                    <span class="bi bi-envelope"></span>
                </div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Mật khẩu" required>
                <div class="input-group-text" style="cursor: pointer;" onclick="togglePassword()">
                    <span class="bi bi-eye" id="eyeIcon"></span>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3">
                <div class="col-8">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="social-auth-links text-center mb-3 d-grid gap-2">
            <p>- HOẶC -</p>
            <a href="{{ route('auth.google') }}" class="btn btn-danger">
                <i class="bi bi-google me-2"></i> Đăng nhập bằng Google
            </a>
        </div>

        <p class="mb-1 text-center">
            <a href="{{ route('home') }}">Về trang chủ</a>
        </p>
        <p class="mb-0 text-center">
            <a href="{{ route('register') }}" class="text-center">Đăng ký tài khoản mới</a>
        </p>
    </div>
</div>

@push('scripts')
<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endpush
@endsection
