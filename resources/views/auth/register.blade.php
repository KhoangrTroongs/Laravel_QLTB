@extends('layouts.auth')
@section('title', 'Đăng Ký')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Đăng ký tài khoản mới</p>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="input-group mb-3">
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Họ và tên" required autofocus>
                <div class="input-group-text">
                    <span class="bi bi-person"></span>
                </div>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="Email" required>
                <div class="input-group-text">
                    <span class="bi bi-envelope"></span>
                </div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="form-control @error('phone') is-invalid @enderror"
                       placeholder="Số điện thoại">
                <div class="input-group-text">
                    <span class="bi bi-telephone"></span>
                </div>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Mật khẩu" required>
                <div class="input-group-text" style="cursor: pointer;" onclick="toggleP()">
                    <span class="bi bi-eye" id="eyeI"></span>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="form-control"
                       placeholder="Xác nhận mật khẩu" required>
                <div class="input-group-text" style="cursor: pointer;" onclick="togglePC()">
                    <span class="bi bi-eye" id="eyeIC"></span>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-8">
                    <!-- Blank to push button to right, or can put "I agree to terms" -->
                </div>
                <div class="col-4">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Đăng Ký</button>
                    </div>
                </div>
            </div>
        </form>

        <p class="mb-1 text-center mt-3">
            <a href="{{ route('login') }}" class="text-center">Đã có tài khoản? Đăng nhập</a>
        </p>
        <p class="mb-0 text-center">
            <a href="{{ route('home') }}" class="text-center">Về trang chủ</a>
        </p>
    </div>
</div>

@push('scripts')
<script>
function toggleP() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeI');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        pwd.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
function togglePC() {
    const pwd = document.getElementById('password_confirmation');
    const icon = document.getElementById('eyeIC');
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
