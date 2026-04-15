@extends('layouts.app')

@section('title', 'Thêm Thiết Bị')
@section('page-title', 'Thêm Thiết Bị Mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Thiết Bị</a></li>
    <li class="breadcrumb-item active">Thêm Mới</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-plus-circle mr-2"></i>Form Thêm Thiết Bị</h3>
            </div>
            <form action="{{ route('equipment.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img id="image-preview" src="https://via.placeholder.com/150?text=Equipment" 
                                 class="shadow border rounded" style="width: 150px; height: 100px; object-fit: cover;">
                            <label for="image" class="position-absolute btn btn-xs btn-primary rounded-circle" style="bottom: -10px; right: -10px; width: 30px; height: 30px; padding-top: 5px;">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" name="image" id="image" class="d-none" accept="image/*" onchange="previewEquipmentImage(this)">
                        </div>
                        <div class="small text-muted mt-2">Hình ảnh thiết bị</div>
                        @error('image') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="name" class="text-muted small font-weight-bold">TÊN THIẾT BỊ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-laptop text-primary"></i></span></div>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror border-left-0"
                                   value="{{ old('name') }}" placeholder="VD: iPhone 15, Laptop Dell, Màn hình LG...">
                        </div>
                        @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="model" class="text-muted small font-weight-bold">MODEL / MÃ SẢN PHẨM <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-barcode text-primary"></i></span></div>
                            <input type="text" name="model" id="model" class="form-control @error('model') is-invalid @enderror border-left-0"
                                   value="{{ old('model') }}" placeholder="VD: A2847, XPS 15, 27UK850-W...">
                        </div>
                        @error('model') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="text-muted small font-weight-bold">THÔNG TIN MÔ TẢ</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="3" placeholder="Mô tả thêm về thiết bị...">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label for="status" class="text-muted small font-weight-bold">TÌNH TRẠNG THIẾT BỊ <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Sẵn sàng sử dụng</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Đang bảo trì/Hư hỏng</option>
                        </select>
                        @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>Lưu Thiết Bị
                    </button>
                    <a href="{{ route('equipment.index') }}" class="btn btn-default ml-2">
                        <i class="fas fa-arrow-left mr-1"></i>Quay Lại
                    </a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Hướng Dẫn</h3>
            </div>
            <div class="card-body">
                <p><strong>Tên Thiết Bị:</strong> Tên đầy đủ của thiết bị (laptop, cáp sạc, màn hình, điện thoại...)</p>
                <p><strong>Model:</strong> Mã model của thiết bị từ nhà sản xuất.</p>
                <p><strong>Trạng Thái:</strong></p>
                <ul>
                    <li><span class="badge badge-success">Hoạt động bình thường</span> - Thiết bị đang hoạt động tốt</li>
                    <li><span class="badge badge-danger">Bị hư</span> - Thiết bị cần sửa chữa</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    function previewEquipmentImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) { $('#image-preview').attr('src', e.target.result); }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection

