@extends('layouts.auth')
@section('title', 'Đăng Ký')

@section('content')
<div class="auth-card card">
    <div class="card-header">
        <div class="auth-brand-icon"><i class="fas fa-user-plus"></i></div>
        <h1 class="auth-brand-title">Tạo Tài Khoản</h1>
        <p class="auth-brand-sub">Đăng ký để sử dụng hệ thống</p>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger rounded mb-3">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li class="small">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label class="font-weight-bold text-muted small">HỌ VÀ TÊN *</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control form-control-auth @error('name') is-invalid @enderror"
                       placeholder="Nguyễn Văn A" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="font-weight-bold text-muted small">EMAIL *</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control form-control-auth @error('email') is-invalid @enderror"
                       placeholder="your@email.com" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="font-weight-bold text-muted small">SỐ ĐIỆN THOẠI</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="form-control form-control-auth @error('phone') is-invalid @enderror"
                       placeholder="0912 345 678">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="font-weight-bold text-muted small">MẬT KHẨU *</label>
                <input type="password" name="password"
                       class="form-control form-control-auth @error('password') is-invalid @enderror"
                       placeholder="Tối thiểu 6 ký tự" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="font-weight-bold text-muted small">XÁC NHẬN MẬT KHẨU *</label>
                <input type="password" name="password_confirmation"
                       class="form-control form-control-auth"
                       placeholder="Nhập lại mật khẩu" required>
            </div>

            <div class="alert alert-info py-2 rounded-lg">
                <i class="fas fa-info-circle mr-1"></i>
                <small>Tài khoản mới sẽ có vai trò <strong>User</strong> mặc định.</small>
            </div>

            <button type="submit" class="btn btn-success btn-auth mt-2 mb-3">
                <i class="fas fa-user-plus mr-2"></i> Đăng Ký Tài Khoản
            </button>
        </form>

        <div class="auth-divider">hoặc</div>

        <div class="text-center mt-3">
            <span class="text-muted small">Đã có tài khoản?</span>
            <a href="{{ route('login') }}" class="font-weight-bold ml-1">Đăng nhập</a>
        </div>
        <div class="text-center mt-2">
            <a href="{{ route('home') }}" class="text-muted small">
                <i class="fas fa-arrow-left mr-1"></i>Về trang chủ
            </a>
        </div>
    </div>
</div>
@endsection
