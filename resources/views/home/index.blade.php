@extends('layouts.public')
@section('title', 'Thiết Bị Cho Mượn')

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="badge badge-pill badge-primary mb-3 px-3 py-2" style="background: rgba(59, 130, 246, 0.2); color: #3b82f6; border: none; font-weight: 700; letter-spacing: 1px;">
                    SẴN SÀNG PHỤC VỤ
                </div>
                <h1 class="display-4 font-weight-bold mb-3">Tối Ưu Hoá <br><span style="color: #3b82f6;">Quản Lý Thiết Bị</span></h1>
                <p class="lead opacity-75 mb-5">Hệ thống mượn và trả thiết bị nội bộ chuyên nghiệp. <br>Đơn giản, minh bạch và hiệu quả tuyệt đối.</p>
                
                <form action="{{ route('home') }}" method="GET" class="hero-search-wrapper" style="max-width: 600px;">
                    <div class="input-group hero-search shadow-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-0 pl-4"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control"
                               placeholder="Bạn đang tìm thiết bị gì?" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-search">
                                Tìm Kiếm
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-center">
                <div class="hero-illustration animate__animated animate__fadeInRight">
                    <div style="font-size: 15rem; color: #3b82f6; opacity: 0.2; filter: blur(2px);">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Equipment Grid -->
<div class="container py-5 mt-n4">
    <div class="bg-white p-4 p-md-5 shadow-sm" style="border-radius: 30px; margin-top: -60px; position: relative; z-index: 10;">
        <!-- Category Filter -->
        <div class="mb-5">
            <h6 class="text-uppercase text-muted font-weight-bold mb-4 small" style="letter-spacing: 2px;">
                <i class="fas fa-filter mr-2"></i>Duyệt Theo Loại
            </h6>
            <div class="d-flex flex-wrap" style="gap: 12px;">
                <a href="{{ route('home', array_merge(request()->except('category_id'))) }}" 
                   class="btn {{ !request('category_id') ? 'btn-primary' : 'btn-light border text-muted' }}" 
                   style="border-radius: 12px; font-weight: 600; padding: 0.6rem 1.25rem;">
                    Tất cả
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('home', array_merge(request()->all(), ['category_id' => $cat->id])) }}" 
                       class="btn {{ request('category_id') == $cat->id ? 'btn-primary' : 'btn-light border text-muted' }}" 
                       style="border-radius: 12px; font-weight: 600; padding: 0.6rem 1.25rem;">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        @if(request('search') || request('category_id'))
            <div class="d-flex align-items-center justify-content-between mb-5">
                <h4 class="mb-0 font-weight-bold">
                    @if(request('search'))
                        <span class="text-muted">Kết quả cho:</span> "{{ request('search') }}"
                    @endif
                    @if(request('category_id'))
                        @php $catName = $categories->where('id', request('category_id'))->first()->name ?? ''; @endphp
                        @if(request('search')) & @endif
                        <span class="text-muted">Loại:</span> {{ $catName }}
                    @endif
                </h4>
                <a href="{{ route('home') }}" class="btn btn-light px-4" style="border-radius: 12px;">
                    <i class="fas fa-times mr-2 text-danger"></i>Xoá bộ lọc
                </a>
            </div>
        @else
            <div class="d-flex align-items-center justify-content-between mb-5">
                <h4 class="mb-0 font-weight-bold">
                    <i class="fas fa-th-large text-primary mr-3"></i>Thiết bị mới nhất
                </h4>
            </div>
        @endif

        @if($availableEquipment->count() > 0)
            <div class="row">
                @foreach($availableEquipment as $item)
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card equipment-card">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}"
                                     class="card-img-top" alt="{{ $item->name }}">
                            @else
                                <div class="card-img-placeholder">
                                    <i class="fas fa-laptop"></i>
                                </div>
                            @endif
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="mb-3">
                                    <span class="badge-available shadow-sm">
                                        <div class="bg-success rounded-circle mr-2" style="width: 8px; height: 8px;"></div>
                                        Sẵn sàng
                                    </span>
                                </div>
                                <h5 class="card-title font-weight-bold text-dark mb-2" style="font-size: 1.1rem; line-height: 1.4;">
                                    {{ $item->name }}
                                </h5>
                                <div class="text-muted small mb-3">
                                    <span class="bg-light px-2 py-1 rounded mr-1" style="font-size: 0.75rem;">
                                        <i class="fas fa-tag mr-1 opacity-50"></i>{{ $item->model }}
                                    </span>
                                    @if($item->category)
                                        <span class="badge badge-info shadow-sm" style="font-size: 0.65rem; border-radius: 8px;">
                                            {{ $item->category->name }}
                                        </span>
                                    @endif
                                </div>
                                
                                @if($item->description)
                                    <p class="text-muted small mb-4" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.6;">
                                        {{ $item->description }}
                                    </p>
                                @endif
                                
                                <div class="mt-auto">
                                    <a href="{{ route('home.show', $item) }}"
                                       class="btn btn-primary d-flex align-items-center justify-content-center shadow-sm" 
                                       style="border-radius: 14px; padding: 0.75rem; font-weight: 700; transition: all 0.3s; background: var(--accent-gradient); border: none;">
                                        Xem Chi Tiết <i class="fas fa-arrow-right ml-2" style="font-size: 0.8rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $availableEquipment->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4" style="font-size: 6rem; opacity: 0.1; color: #3b82f6;">
                    <i class="fas fa-search"></i>
                </div>
                <h4 class="text-dark font-weight-bold">Không tìm thấy thiết bị phù hợp</h4>
                <p class="text-muted">Thử tìm kiếm với từ khoá khác hoặc xem danh sách tất cả.</p>
                @if(request('search'))
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg mt-3 px-5" style="border-radius: 15px;">
                        <i class="fas fa-sync-alt mr-2"></i>Xem tất cả thiết bị
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
