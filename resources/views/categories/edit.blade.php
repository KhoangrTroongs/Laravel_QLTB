@extends('layouts.app')

@section('title', 'Chỉnh Sửa Loại Thiết Bị')
@section('page-title', 'Sửa Loại Thiết Bị')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Loại Thiết Bị</a></li>
    <li class="breadcrumb-item active">Chỉnh Sửa</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card card-outline card-warning shadow-sm" style="border-radius: 12px;">
            <div class="card-header">
                <h3 class="card-title font-weight-bold"><i class="fas fa-edit mr-2"></i>Cập nhật loại thiết bị</h3>
            </div>
            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Tên loại <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $category->name) }}" required autofocus>
                        @error('name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="small font-weight-bold text-muted text-uppercase">Mô tả</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="2">{{ old('description', $category->description) }}</textarea>
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
                        
                        <div id="specs-container">
                            @if($category->specs && is_array($category->specs))
                                @foreach($category->specs as $spec)
                                    <div class="input-group mb-2 spec-item">
                                        <input type="text" name="specs[]" class="form-control" value="{{ $spec }}" placeholder="Tên thuộc tính">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-danger remove-spec" type="button">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2 spec-item">
                                    <input type="text" name="specs[]" class="form-control" placeholder="Tên thuộc tính (VD: RAM)">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-danger remove-spec" type="button">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-top d-flex justify-content-end">
                    <a href="{{ route('categories.index') }}" class="btn btn-default mr-2">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-warning px-5 shadow-sm font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Cập nhật ngay
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
                    <input type="text" name="specs[]" class="form-control" placeholder="Tên thuộc tính">
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
