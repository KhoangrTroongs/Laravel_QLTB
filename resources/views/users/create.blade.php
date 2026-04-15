@extends('layouts.app')

@section('title', 'Thêm Nhân Viên')
@section('page-title', 'Thêm Nhân Viên Mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Nhân Viên</a></li>
    <li class="breadcrumb-item active">Thêm Mới</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-plus mr-2"></i>Form Thêm Nhân Viên</h3>
            </div>
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img id="avatar-preview" src="https://ui-avatars.com/api/?name=New+User&size=128" 
                                 class="rounded-circle shadow border" style="width: 120px; height: 120px; object-fit: cover;">
                            <label for="avatar" class="position-absolute btn btn-xs btn-primary rounded-circle" style="bottom: 5px; right: 5px; width: 30px; height: 30px; padding-top: 5px;">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" name="avatar" id="avatar" class="d-none" accept="image/*" onchange="previewImage(this)">
                        </div>
                        <div class="small text-muted mt-2">Ảnh đại diện nhân viên</div>
                        @error('avatar') <div class="text-danger small">{{ $message }}</div> @enderror
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

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="employee_id" class="text-muted small font-weight-bold">MÃ NHÂN VIÊN <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-id-card text-primary"></i></span></div>
                                    <input type="text" name="employee_id" id="employee_id" class="form-control @error('employee_id') is-invalid @enderror border-left-0" value="{{ old('employee_id') }}" placeholder="VD: NV001">
                                </div>
                                @error('employee_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="text-muted small font-weight-bold">TÊN NHÂN VIÊN <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-user text-primary"></i></span></div>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror border-left-0" value="{{ old('name') }}" placeholder="Họ và tên đầy đủ">
                                </div>
                                @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="text-muted small font-weight-bold">ĐỊA CHỈ EMAIL <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-envelope text-primary"></i></span></div>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror border-left-0" value="{{ old('email') }}" placeholder="email@example.com">
                                </div>
                                @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="text-muted small font-weight-bold">SỐ ĐIỆN THOẠI</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-phone text-primary"></i></span></div>
                                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror border-left-0" value="{{ old('phone') }}" placeholder="0900 000 000">
                                </div>
                                @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address" class="text-muted small font-weight-bold">ĐỊA CHỈ THƯỜNG TRÚ</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-map-marker-alt text-primary"></i></span></div>
                            <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror border-left-0" value="{{ old('address') }}" placeholder="Địa chỉ chi tiết">
                        </div>
                        @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="text-muted small font-weight-bold">MẬT KHẨU ĐĂNG NHẬP <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text bg-light border-right-0"><i class="fas fa-lock text-primary"></i></span></div>
                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror border-left-0" placeholder="Tối thiểu 6 ký tự">
                                </div>
                                @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="text-muted small font-weight-bold">TRẠNG THÁI CÔNG VIỆC <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Đang làm việc</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Đã nghỉ việc</option>
                                </select>
                                @error('status') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i>Lưu Nhân Viên
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-default ml-2">
                        <i class="fas fa-arrow-left mr-1"></i>Quay Lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) { $('#avatar-preview').attr('src', e.target.result); }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection

