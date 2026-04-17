@extends('layouts.app')

@section('title', 'Thêm Thiết Bị')
@section('page-title', 'Thêm Thiết Bị Mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Thiết Bị</a></li>
    <li class="breadcrumb-item active">Thêm Mới</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-9">
        <div class="card card-primary card-outline shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold"><i class="fas fa-plus-circle mr-2 text-primary"></i>Thông tin thiết bị</h3>
            </div>
            <form action="{{ route('equipment.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center border-right">
                            <div class="position-relative d-inline-block mt-4">
                                <img id="image-preview" src="https://via.placeholder.com/200?text=Hình+ảnh" 
                                     class="shadow-sm border rounded" style="width: 200px; height: 150px; object-fit: cover;">
                                <label for="image" class="position-absolute btn btn-sm btn-primary rounded-circle shadow" 
                                       style="bottom: -10px; right: -10px; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" name="image" id="image" class="d-none" accept="image/*" onchange="previewEquipmentImage(this)">
                            </div>
                            <p class="small text-muted mt-3">Tải lên hình ảnh minh họa thiết bị</p>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase">Tên thiết bị <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" placeholder="VD: MacBook Pro 14 inch, Dell UltraSharp...">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="text-muted small font-weight-bold text-uppercase">Model / Mã SP <span class="text-danger">*</span></label>
                                        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" 
                                               value="{{ old('model') }}" placeholder="VD: MKGP3LL/A...">
                                        @error('model') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="text-muted small font-weight-bold text-uppercase">Loại thiết bị <span class="text-danger">*</span></label>
                                        <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                            <option value="">-- Chọn loại --</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase">Tình trạng</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Sẵn sàng sử dụng</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Đang bảo trì/Hư hỏng</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="text-muted small font-weight-bold text-uppercase">Mô tả thêm</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Nhập ghi chú hoặc mô tả sơ lược...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div id="specs-section" class="mt-4 pt-3 border-top" style="display: none;">
                        <h5 class="text-primary font-weight-bold mb-3"><i class="fas fa-microchip mr-2"></i>Thông số kỹ thuật</h5>
                        <div id="dynamic-specs-container" class="row">
                            <!-- JS will inject fields here -->
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white border-top d-flex justify-content-between">
                    <a href="{{ route('equipment.index') }}" class="btn btn-default">
                        <i class="fas fa-arrow-left mr-1"></i> Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary px-5 shadow-sm font-weight-bold">
                        <i class="fas fa-save mr-1"></i> Lưu thiết bị
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-info card-outline shadow-sm">
            <div class="card-header"><h3 class="card-title font-weight-bold"><i class="fas fa-info-circle mr-2"></i>Hướng dẫn</h3></div>
            <div class="card-body small">
                <p class="mb-2"><strong>Thông số kỹ thuật:</strong> Khi bạn chọn một <strong>Loại thiết bị</strong>, các ô nhập liệu tương ứng sẽ xuất hiện.</p>
                <p class="text-muted italic">Các thông số này được Admin định nghĩa trong menu "Loại Thiết Bị".</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Data injection from Blade to JS
    const categoriesSpecs = @json($categories->pluck('specs', 'id'));

    $(document).ready(function() {
        $('#category_id').change(function() {
            const categoryId = $(this).val();
            const container = $('#dynamic-specs-container');
            const section = $('#specs-section');
            
            container.empty();
            
            if (categoryId && categoriesSpecs[categoryId] && categoriesSpecs[categoryId].length > 0) {
                const specs = categoriesSpecs[categoryId];
                
                specs.forEach(specName => {
                    const html = `
                        <div class="col-md-6 mb-3 animate__animated animate__fadeIn">
                            <div class="form-group mb-0">
                                <label class="text-muted small font-weight-bold text-uppercase">${specName}</label>
                                <input type="text" name="specs[${specName}]" class="form-control" placeholder="Nhập ${specName}...">
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

        // Trigger change if editing or validation fails
        if ($('#category_id').val()) {
            $('#category_id').change();
        }
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
