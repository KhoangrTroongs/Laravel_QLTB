@extends('layouts.public')
@section('title', 'Thiết Bị Cho Mượn')

@section('content')
<!-- Hero Section -->
<div class="text-center bg-primary text-white position-relative" style="background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%); padding: 100px 0;">
    <div class="container position-relative z-3">
        <span class="badge bg-white text-primary mb-3 px-3 py-2 rounded-pill shadow-sm fw-bold">
            <i class="fas fa-check-circle me-1"></i> LUÔN SẴN SÀNG PHỤC VỤ
        </span>
        <h1 class="display-4 fw-bold text-white mb-4">Quản Lý Thiết Bị Dễ Dàng</h1>
        <div class="col-lg-6 mx-auto">
            <p class="lead mb-5 text-white-50">Nền tảng quản lý mượn trả trang thiết bị nội bộ hiện đại, minh bạch. Hàng trăm thiết bị xịn xò đang chờ bạn khám phá.</p>
            
            <div class="d-flex justify-content-center">
                <form action="{{ route('home') }}" method="GET" class="w-100" style="max-width: 650px;">
                    <div class="input-group input-group-lg shadow-lg rounded-pill overflow-hidden bg-white p-2">
                        <span class="input-group-text bg-white border-0 ps-4 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control border-0 bg-white"
                               placeholder="Bạn đang tìm thiết bị gì hôm nay?" value="{{ request('search') }}" style="box-shadow: none;">
                        <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                            Tìm Kiếm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Background Decoration -->
    <div class="position-absolute top-50 start-0 translate-middle-y opacity-10 d-none d-lg-block" style="font-size: 25rem; margin-left: -50px; line-height: 1;">
        <i class="fas fa-desktop"></i>
    </div>
    <div class="position-absolute top-50 end-0 translate-middle-y opacity-10 d-none d-lg-block" style="font-size: 20rem; margin-right: -20px; line-height: 1;">
        <i class="fas fa-camera"></i>
    </div>
</div>

<!-- Main Content (Album) -->
<div class="bg-body-tertiary py-5">
    <div class="container">
        
        <!-- Category Pill Tabs -->
        <div class="mb-5 text-center">
            <div class="d-inline-flex flex-wrap justify-content-center gap-2 p-2 bg-white rounded-pill shadow-sm border">
                <a href="{{ route('home', array_merge(request()->except('category_id'))) }}" 
                   class="btn rounded-pill px-4 fw-semibold {{ !request('category_id') ? 'btn-primary shadow-sm' : 'btn-white text-muted border-0 hover-bg-light' }}">
                    <i class="fas fa-layer-group me-1"></i> Tất cả
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('home', array_merge(request()->all(), ['category_id' => $cat->id])) }}" 
                       class="btn rounded-pill px-4 fw-semibold {{ request('category_id') == $cat->id ? 'btn-primary shadow-sm' : 'btn-white text-muted border-0 hover-bg-light' }}">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Filter Status -->
        @if(request('search') || request('category_id'))
            <div class="d-flex align-items-center justify-content-between mb-4 bg-white p-3 rounded-3 shadow-sm border-start border-4 border-primary">
                <h5 class="mb-0 fw-bold text-dark">
                    @if(request('search'))
                        <span class="text-muted fw-normal">Kết quả cho:</span> <span class="text-primary">"{{ request('search') }}"</span>
                    @endif
                    @if(request('category_id'))
                        @php $catName = $categories->where('id', request('category_id'))->first()->name ?? ''; @endphp
                        @if(request('search')) <span class="mx-2 text-muted">|</span> @endif
                        <span class="text-muted fw-normal">Danh mục:</span> <span class="text-primary">{{ $catName }}</span>
                    @endif
                </h5>
                <a href="{{ route('home') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                    <i class="fas fa-times me-1"></i> Xoá bộ lọc
                </a>
            </div>
        @else
            <div class="d-flex align-items-center mb-4">
                <h4 class="mb-0 fw-bold">
                    <i class="fas fa-fire-alt text-warning me-2"></i> Thiết bị nổi bật
                </h4>
            </div>
        @endif

        <!-- Equipment Grid -->
        @if($availableEquipment->count() > 0)
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach($availableEquipment as $item)
                    <div class="col">
                        <div class="card h-100 equipment-card shadow-sm border-0 rounded-4 overflow-hidden">
                            <!-- Image container with fixed aspect ratio -->
                            <div class="position-relative bg-light" style="padding-top: 75%;">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                         class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" alt="{{ $item->name }}">
                                @else
                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center text-muted" style="background: #f8f9fa;">
                                        <i class="fas fa-laptop" style="font-size: 4rem; opacity: 0.2;"></i>
                                    </div>
                                @endif
                                
                                <!-- Status Badge Overlay -->
                                <div class="position-absolute top-0 end-0 p-3">
                                    <span class="badge bg-success shadow-sm rounded-pill px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i> Sẵn sàng
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-body p-4 d-flex flex-column">
                                @if($item->category)
                                    <div class="mb-2">
                                        <span class="text-uppercase text-primary fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
                                            {{ $item->category->name }}
                                        </span>
                                    </div>
                                @endif
                                
                                <h5 class="card-title fw-bold text-dark mb-1" style="font-size: 1.25rem;">
                                    {{ $item->name }}
                                </h5>
                                
                                <p class="text-muted small mb-3">
                                    <i class="fas fa-hashtag me-1 opacity-50"></i>{{ $item->model }}
                                </p>
                                
                                @if($item->description)
                                    <p class="text-muted small mb-4 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $item->description }}
                                    </p>
                                @endif
                                
                                <div class="mt-auto">
                                    <a href="{{ route('home.show', $item) }}" class="btn btn-outline-primary w-100 rounded-pill fw-bold" style="transition: all 0.3s;">
                                        Xem Chi Tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $availableEquipment->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-dashed">
                <div class="mb-4" style="font-size: 5rem; opacity: 0.2; color: #0d6efd;">
                    <i class="fas fa-box-open"></i>
                </div>
                <h3 class="text-dark fw-bold mb-3">Không tìm thấy thiết bị</h3>
                <p class="text-muted mb-4">Chúng tôi không tìm thấy thiết bị nào phù hợp với điều kiện lọc của bạn.</p>
                @if(request('search') || request('category_id'))
                    <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-5 shadow-sm">
                        <i class="fas fa-sync-alt me-2"></i>Xem tất cả thiết bị
                    </a>
                @endif
            </div>
        @endif
        
    </div>
</div>

<style>
    .equipment-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .equipment-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .equipment-card:hover .btn-outline-primary {
        background-color: #0d6efd;
        color: white;
    }
    .hover-bg-light:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
