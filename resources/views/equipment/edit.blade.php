@extends('layouts.app')

@section('title', 'Sửa Thiết Bị')
@section('page-title', 'Sửa Thông Tin Thiết Bị')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Thiết Bị</a></li>
    <li class="breadcrumb-item active">Sửa</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-edit mr-2"></i>Sửa Thiết Bị: {{ $equipment->name }}</h3>
            </div>
            <form action="{{ route('equipment.update', $equipment) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            @if($equipment->image)
                                <img id="image-preview" src="{{ asset('storage/' . $equipment->image) }}" 
                                     class="shadow border rounded" style="width: 150px; height: 100px; object-fit: cover;">
                            @else
                                <img id="image-preview" src="https://via.placeholder.com/150?text=Equipment" 
                                     class="shadow border rounded" style="width: 150px; height: 100px; object-fit: cover;">
                            @endif
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
                        <label for="name">Tên Thiết Bị <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $equipment->name) }}" placeholder="VD: iPhone 15, Laptop Dell...">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="model">Model <span class="text-danger">*</span></label>
                        <input type="text" name="model" id="model" class="form-control @error('model') is-invalid @enderror"
                               value="{{ old('model', $equipment->model) }}" placeholder="VD: A2847, XPS 15...">
                        @error('model')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Thông Tin Mô Tả</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                  rows="3" placeholder="Mô tả thêm về thiết bị...">{{ old('description', $equipment->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Trạng Thái <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="1" {{ old('status', $equipment->status) == '1' ? 'selected' : '' }}>Hoạt động bình thường</option>
                            <option value="0" {{ old('status', $equipment->status) == '0' ? 'selected' : '' }}>Bị hư</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning shadow-sm">
                        <i class="fas fa-save mr-1"></i>Cập Nhật Thiết Bị
                    </button>
                    <a href="{{ route('equipment.index') }}" class="btn btn-default ml-2 shadow-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Quay Lại
                    </a>
                    <button type="button" class="btn btn-danger float-right shadow-sm" onclick="$('#delete-form').submit()">
                        <i class="fas fa-trash mr-1"></i>Xóa Thiết Bị
                    </button>
                </div>
            </form>
            <form id="delete-form" action="{{ route('equipment.destroy', $equipment) }}" method="POST" style="display: none;" class="delete-form">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info mr-2"></i>Thông Tin Hiện Tại</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><th>ID:</th><td>{{ $equipment->id }}</td></tr>
                    <tr><th>Tên:</th><td>{{ $equipment->name }}</td></tr>
                    <tr><th>Model:</th><td>{{ $equipment->model }}</td></tr>
                    <tr>
                        <th>Trạng thái:</th>
                        <td>
                            @if($equipment->status)
                                <span class="badge badge-success">Hoạt động</span>
                            @else
                                <span class="badge badge-danger">Bị hư</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>Cập nhật:</th><td>{{ $equipment->updated_at->format('d/m/Y H:i') }}</td></tr>
                </table>
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

