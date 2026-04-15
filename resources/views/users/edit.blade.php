@extends('layouts.app')

@section('title', 'Sửa Nhân Viên')
@section('page-title', 'Sửa Thông Tin Nhân Viên')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Nhân Viên</a></li>
    <li class="breadcrumb-item active">Sửa</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-edit mr-2"></i>Sửa: {{ $user->name }}</h3>
            </div>
            <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            @if($user->avatar)
                                @php
                                    $avatarUrl = str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar);
                                @endphp
                                <img id="avatar-preview" src="{{ $avatarUrl }}" 
                                     class="rounded-circle shadow border" style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <img id="avatar-preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128" 
                                     class="rounded-circle shadow border" style="width: 120px; height: 120px; object-fit: cover;">
                            @endif
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
                                <label for="employee_id">Mã Nhân Viên <span class="text-danger">*</span></label>
                                <input type="text" name="employee_id" id="employee_id"
                                       class="form-control @error('employee_id') is-invalid @enderror"
                                       value="{{ old('employee_id', $user->employee_id) }}">
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Tên Nhân Viên <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Số Điện Thoại</label>
                                <input type="text" name="phone" id="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address">Địa Chỉ</label>
                        <input type="text" name="address" id="address"
                               class="form-control @error('address') is-invalid @enderror"
                               value="{{ old('address', $user->address) }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Mật Khẩu Mới <small class="text-muted">(để trống nếu không đổi)</small></label>
                                <input type="password" name="password" id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Tối thiểu 6 ký tự">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Trạng Thái <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" {{ $user->employee_id === 'ADMIN001' ? 'disabled' : '' }}>
                                    <option value="1" {{ old('status', $user->status) == '1' ? 'selected' : '' }}>Đang làm việc</option>
                                    <option value="0" {{ old('status', $user->status) == '0' ? 'selected' : '' }}>Đã nghỉ việc</option>
                                </select>
                                @if($user->employee_id === 'ADMIN001')
                                    <input type="hidden" name="status" value="1">
                                    <small class="text-info"><i class="fas fa-info-circle mr-1"></i>Tài khoản Admin chính luôn ở trạng thái Hoạt động.</small>
                                @endif
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-warning shadow-sm">
                        <i class="fas fa-save mr-1"></i>Cập Nhật Thông Tin
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-default ml-2 shadow-sm">
                        <i class="fas fa-arrow-left mr-1"></i>Quay Lại
                    </a>
                    @if($user->employee_id !== 'ADMIN001')
                        <button type="button" class="btn btn-danger float-right shadow-sm" onclick="if(confirm('Bạn có chắc muốn xóa nhân viên này?')) document.getElementById('delete-form').submit();">
                            <i class="fas fa-trash mr-1"></i>Xóa Nhân Viên
                        </button>
                    @endif
                </div>
            </form>
            <form id="delete-form" action="{{ route('users.destroy', $user) }}" method="POST" style="display: none;">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-id-card mr-2"></i>Thông Tin Hiện Tại</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><th>Mã NV:</th><td><code>{{ $user->employee_id }}</code></td></tr>
                    <tr><th>Tên:</th><td>{{ $user->name }}</td></tr>
                    <tr><th>Email:</th><td>{{ $user->email }}</td></tr>
                    <tr><th>SĐT:</th><td>{{ $user->phone ?? '-' }}</td></tr>
                    <tr>
                        <th>Trạng thái:</th>
                        <td>
                            @if($user->status)
                                <span class="badge badge-success">Đang làm</span>
                            @else
                                <span class="badge badge-secondary">Đã nghỉ</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
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

