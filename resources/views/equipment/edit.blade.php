@extends('layouts.app')

@section('title', 'Sửa Thiết Bị')
@section('page-title', 'Chỉnh Sửa Thiết Bị')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Thiết Bị</a></li>
    <li class="breadcrumb-item active">Chỉnh Sửa</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-9">
        <div class="card card-warning card-outline shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold text-warning-emphasis"><i class="fas fa-edit mr-2"></i>Sửa: {{ $equipment->name }}</h3>
            </div>
            <form action="{{ route('equipment.update', $equipment) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center border-right">
                            <div class="position-relative d-inline-block mt-4">
                                <img id="image-preview" src="{{ $equipment->image ? asset('storage/' . $equipment->image) : 'https://via.placeholder.com/200?text=No+Image' }}" 
                                     class="shadow-sm border rounded" style="width: 200px; height: 150px; object-fit: cover;">
                                <label for="image" class="position-absolute btn btn-sm btn-warning rounded-circle shadow" 
                                       style="bottom: -10px; right: -10px; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-camera text-white"></i>
                                </label>
                                <input type="file" name="image" id="image" class="d-none" accept="image/*" onchange="previewEquipmentImage(this)">
                            </div>
                            <p class="small text-muted mt-3">Thay đổi hình ảnh minh họa</p>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase">Tên thiết bị <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $equipment->name) }}">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="text-muted small font-weight-bold text-uppercase">Model / Mã SP <span class="text-danger">*</span></label>
                                        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" 
                                               value="{{ old('model', $equipment->model) }}">
                                        @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="text-muted small font-weight-bold text-uppercase">Loại thiết bị <span class="text-danger">*</span></label>
                                        <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category_id', $equipment->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="text-muted small font-weight-bold text-uppercase">Tình trạng</label>
                                        <select name="status" class="form-control">
                                            <option value="1" {{ old('status', $equipment->status) == '1' ? 'selected' : '' }}>Sẵn sàng sử dụng</option>
                                            <option value="0" {{ old('status', $equipment->status) == '0' ? 'selected' : '' }}>Đang bảo trì/Hư hỏng</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="text-muted small font-weight-bold text-uppercase">Trạng thái rác</label>
                                        <div class="pt-2">
                                            @if($equipment->trashed())
                                                <span class="badge badge-danger px-3 py-2">Đã xóa mềm</span>
                                            @else
                                                <span class="badge badge-success px-3 py-2">Đang hoạt động</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase">Mô tả thêm</label>
                                <textarea name="description" class="form-control" rows="2">{{ old('description', $equipment->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div id="specs-section" class="mt-4 pt-3 border-top">
                        <h5 class="text-warning font-weight-bold mb-3"><i class="fas fa-microchip mr-2"></i>Thông số kỹ thuật</h5>
                        <div id="dynamic-specs-container" class="row">
                            <!-- JS will inject fields here -->
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-top d-flex justify-content-end">
                    <a href="{{ route('equipment.index') }}" class="btn btn-default mr-2">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-warning px-6 shadow-sm font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-outline card-warning shadow-sm">
            <div class="card-header"><h3 class="card-title font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Thông tin thêm</h3></div>
            <div class="card-body small">
                <p><strong>Ngày tạo:</strong> {{ $equipment->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Cập nhật:</strong> {{ $equipment->updated_at->format('d/m/Y H:i') }}</p>
                <hr>
                <form action="{{ route('equipment.destroy', $equipment) }}" method="POST" class="delete-form">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-block btn-sm">
                        <i class="fas fa-trash-alt mr-1"></i> Xóa thiết bị
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const categoriesSpecs = @json($categories->pluck('specs', 'id'));
    const currentSpecs = @json($equipment->spec ?? []);

    $(document).ready(function() {
        $('#category_id').change(function() {
            const categoryId = $(this).val();
            const container = $('#dynamic-specs-container');
            const section = $('#specs-section');
            
            container.empty();
            
            if (categoryId && categoriesSpecs[categoryId] && categoriesSpecs[categoryId].length > 0) {
                const specs = categoriesSpecs[categoryId];
                
                specs.forEach(specName => {
                    const value = currentSpecs[specName] || '';
                    const html = `
                        <div class="col-md-6 mb-3 animate__animated animate__fadeIn">
                            <div class="form-group mb-0">
                                <label class="text-muted small font-weight-bold text-uppercase">${specName}</label>
                                <input type="text" name="specs[${specName}]" class="form-control" 
                                       placeholder="Nhập ${specName}..." value="${value}">
                            </div>
                        </div>
                    `;
                    container.append(html);
                });
                
                section.slideDown();
            } else {
                section.slideUp();
            }
        });

        // Trigger on load
        $('#category_id').change();
    });

    function previewEquipmentImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) { $('#image-preview').attr('src', e.target.result); }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
