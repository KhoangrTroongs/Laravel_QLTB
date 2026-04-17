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

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-muted small">EMAIL *</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control form-control-auth @error('email') is-invalid @enderror"
                               placeholder="your@email.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-muted small">SỐ ĐIỆN THOẠI</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="form-control form-control-auth @error('phone') is-invalid @enderror"
                               placeholder="0912 345 678">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-muted small">MẬT KHẨU *</label>
                        <div class="input-group-auth">
                            <input type="password" name="password" id="password"
                                   class="form-control form-control-auth @error('password') is-invalid @enderror"
                                   placeholder="Tối thiểu 6 ký tự" required>
                            <button type="button" class="btn-toggle-pwd" id="toggleP"
                                    onclick="toggleP()">
                                <i class="fas fa-eye" id="eyeI"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-muted small">XÁC NHẬN MẬT KHẨU *</label>
                        <div class="input-group-auth">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control form-control-auth"
                                   placeholder="Lại mật khẩu" required>
                            <button type="button" class="btn-toggle-pwd" id="togglePC"
                                    onclick="togglePC()">
                                <i class="fas fa-eye" id="eyeIC"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success btn-auth mb-3">
                <i class="fas fa-user-plus mr-2"></i> Đăng Ký Ngay
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

@push('scripts')
<script>
function toggleP() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeI');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        pwd.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
function togglePC() {
    const pwd = document.getElementById('password_confirmation');
    const icon = document.getElementById('eyeIC');
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
