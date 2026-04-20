@extends('layouts.public')
@section('title', 'Tạo Phiếu Mượn - ' . $equipment->name)

@section('content')
<div class="container py-5" style="max-width: 700px;">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" style="background: transparent; padding: 0;">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang Chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('home.show', $equipment) }}">{{ $equipment->name }}</a></li>
            <li class="breadcrumb-item active">Tạo Phiếu Mượn</li>
        </ol>
    </nav>

    <div class="card" style="border-radius: 24px; border: none; box-shadow: 0 15px 40px rgba(0,0,0,0.08); overflow: hidden;">
        <div class="card-header" style="background: linear-gradient(135deg, #3b82f6, #2563eb); padding: 2rem; border: none;">
            <h4 class="mb-0 text-white font-weight-bold">
                <i class="fas fa-handshake mr-2"></i> Tạo Phiếu Mượn Thiết Bị
            </h4>
            <p class="mb-0 mt-1 text-white" style="opacity: 0.8;">{{ Auth::user()->name }}</p>
        </div>
        <div class="card-body p-4">
            <!-- Thông tin thiết bị -->
            <div class="d-flex align-items-center mb-4 p-3"
                 style="background: #f8fafc; border-radius: 14px; border: 1px solid #e2e8f0;">
                @if($equipment->image)
                    <img src="{{ asset('storage/' . $equipment->image) }}"
                         style="width: 70px; height: 50px; object-fit: cover; border-radius: 10px;" alt="">
                @else
                    <div style="width: 70px; height: 50px; border-radius: 10px; background: #e2e8f0;
                                display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #94a3b8;">
                        <i class="fas fa-laptop"></i>
                    </div>
                @endif
                <div class="ml-3">
                    <div class="font-weight-bold text-dark">{{ $equipment->name }}</div>
                    <small class="text-muted">{{ $equipment->model }}</small>
                </div>
                <div class="ml-auto">
                    <span class="badge" style="background: #d1fae5; color: #065f46; padding: 0.4rem 0.85rem; border-radius: 8px; font-weight: 700;">
                        <i class="fas fa-check-circle mr-1"></i>Sẵn có
                    </span>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('home.borrow.store', $equipment) }}">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="font-weight-bold text-muted small">NGƯỜI MƯỢN</label>
                        <div class="form-control d-flex align-items-center" style="background: #f1f5f9; border-radius: 12px; border: 1px solid #e2e8f0; height: 50px;">
                            <i class="fas fa-user mr-2 text-primary"></i> {{ Auth::user()->name }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="font-weight-bold text-muted small">NGÀY MƯỢN</label>
                        <div class="form-control d-flex align-items-center" style="background: #f1f5f9; border-radius: 12px; border: 1px solid #e2e8f0; height: 50px;">
                            <i class="fas fa-calendar-check mr-2 text-success"></i> {{ now()->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="font-weight-bold text-muted small">HẠN TRẢ DỰ KIẾN</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background: #fff; border-right: none; border-radius: 12px 0 0 12px; border: 1.5px solid #e2e8f0;">
                                    <i class="fas fa-calendar-alt text-danger"></i>
                                </span>
                            </div>
                            <input type="date" name="hantra" class="form-control text-center @error('hantra') is-invalid @enderror" 
                                   style="border-radius: 0 12px 12px 0; border: 1.5px solid #e2e8f0; border-left: none; height: 50px; font-weight: 600;"
                                   value="{{ old('hantra', now()->addDays(14)->format('Y-m-d')) }}">
                            @error('hantra')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold text-muted small">GHI CHÚ (không bắt buộc)</label>
                    <textarea name="description" rows="3"
                              class="form-control @error('description') is-invalid @enderror"
                              style="border-radius: 12px; border: 1.5px solid #e2e8f0; padding: 0.85rem 1.25rem;"
                              placeholder="Mục đích sử dụng, ghi chú thêm...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-warning py-2" style="border-radius: 12px;">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <small>Vui lòng giữ gìn thiết bị cẩn thận và trả đúng hạn. Thiết bị sẽ được ghi nhận mượn bởi <strong>{{ Auth::user()->name }}</strong>.</small>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg flex-fill"
                            style="border-radius: 14px; font-weight: 700; padding: 1rem;">
                        <i class="fas fa-check mr-2"></i> Xác Nhận Mượn
                    </button>
                    <a href="{{ route('home.show', $equipment) }}"
                       class="btn btn-outline-secondary btn-lg" style="border-radius: 14px; padding: 1rem 2rem; font-weight: 600;">
                        Huỷ
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
