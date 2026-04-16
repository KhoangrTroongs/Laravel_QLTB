@extends('layouts.public')
@section('title', $equipment->name . ' - Chi Tiết Thiết Bị')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background: transparent; padding: 0;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang Chủ</a></li>
            <li class="breadcrumb-item active">{{ $equipment->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Ảnh thiết bị -->
        <div class="col-md-5 mb-4">
            <div class="card" style="border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.07);">
                @if($equipment->image)
                    <img src="{{ asset('storage/' . $equipment->image) }}"
                         style="width: 100%; height: 320px; object-fit: cover;" alt="{{ $equipment->name }}">
                @else
                    <div style="height: 320px; display: flex; align-items: center; justify-content: center;
                                background: linear-gradient(135deg, #e2e8f0, #f1f5f9); font-size: 8rem; color: #94a3b8;">
                        <i class="fas fa-laptop"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Thông tin -->
        <div class="col-md-7">
            <div class="card" style="border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.07);">
                <div class="card-body p-4">
                    <div class="mb-3">
                        @if($isAvailable)
                            <span class="badge" style="background: #d1fae5; color: #065f46; padding: 0.5rem 1rem; border-radius: 10px; font-weight: 700;">
                                <i class="fas fa-check-circle mr-1"></i>Sẵn sàng cho mượn
                            </span>
                        @else
                            <span class="badge badge-danger" style="padding: 0.5rem 1rem; border-radius: 10px; font-weight: 700;">
                                <i class="fas fa-times-circle mr-1"></i>Đang được mượn / Không khả dụng
                            </span>
                        @endif
                    </div>

                    <h2 class="font-weight-bold text-dark mb-1" style="font-size: 1.8rem; letter-spacing: -0.025em;">
                        {{ $equipment->name }}
                    </h2>
                    <p class="text-muted mb-4">
                        <i class="fas fa-tag mr-1"></i> Model: <strong>{{ $equipment->model }}</strong>
                    </p>

                    @if($equipment->description)
                        <div class="mb-4">
                            <h6 class="font-weight-bold text-dark mb-2">Mô tả thiết bị</h6>
                            <p class="text-muted">{{ $equipment->description }}</p>
                        </div>
                    @endif

                    <hr>

                    @if($isAvailable)
                        @auth
                            <a href="{{ route('home.borrow.create', $equipment) }}"
                               class="btn btn-primary btn-lg btn-block" style="border-radius: 14px; font-weight: 700; padding: 1rem;">
                                <i class="fas fa-hands mr-2"></i> Tạo Phiếu Mượn Ngay
                            </a>
                        @else
                            <div class="alert alert-info text-center" style="border-radius: 12px;">
                                <i class="fas fa-info-circle mr-1"></i>
                                <a href="{{ route('login') }}" class="font-weight-bold">Đăng nhập</a> để tạo phiếu mượn thiết bị này.
                            </div>
                        @endauth
                    @else
                        <button class="btn btn-secondary btn-lg btn-block" disabled style="border-radius: 14px; font-weight: 700; padding: 1rem;">
                            <i class="fas fa-ban mr-2"></i> Không Thể Mượn
                        </button>
                    @endif

                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-block mt-3"
                       style="border-radius: 14px; font-weight: 600;">
                        <i class="fas fa-arrow-left mr-2"></i> Quay Lại Danh Sách
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
