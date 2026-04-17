@extends('layouts.app')

@section('title', 'Thêm Loại Thiết Bị Mới')
@section('page-title', 'Thêm Loại Thiết Bị')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Loại Thiết Bị</a></li>
    <li class="breadcrumb-item active">Thêm Mới</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card card-outline card-primary shadow-sm" style="border-radius: 12px;">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-plus mr-2"></i>Thông tin loại thiết bị</h3>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Tên loại <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="VD: Laptop, Monitor, Headset..." required autofocus>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Mô tả</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="2" placeholder="Nhập mô tả sơ lược về loại thiết bị này...">{{ old('description') }}</textarea>
                    </div>

                    <hr>

                    <div class="form-group mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="small font-weight-bold text-muted text-uppercase mb-0">
                                <i class="fas fa-cog mr-1"></i> Định nghĩa thông số kỹ thuật (Specs)
                            </label>
                            <button type="button" class="btn btn-xs btn-success rounded-pill px-3" id="add-spec">
                                <i class="fas fa-plus mr-1"></i> Thêm thuộc tính
                            </button>
                        </div>
                        <p class="text-muted small mb-3">Ví dụ: Nếu là Laptop, hãy thêm các thuộc tính như RAM, CPU, GPU...</p>
                        
                        <div id="specs-container">
                            <div class="input-group mb-2 spec-item animate__animated animate__fadeIn">
                                <input type="text" name="specs[]" class="form-control" placeholder="Tên thuộc tính (VD: RAM)">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-danger remove-spec" type="button">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-flex justify-content-between">
                    <a href="{{ route('categories.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="fas fa-save mr-1"></i> Lưu thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#add-spec').click(function() {
            const html = `
                <div class="input-group mb-2 spec-item animate__animated animate__fadeIn">
                    <input type="text" name="specs[]" class="form-control" placeholder="Tên thuộc tính (VD: CPU)">
                    <div class="input-group-append">
                        <button class="btn btn-outline-danger remove-spec" type="button">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#specs-container').append(html);
        });

        $(document).on('click', '.remove-spec', function() {
            $(this).closest('.spec-item').fadeOut(300, function() {
                $(this).remove();
            });
        });
    });
</script>
@endpush
