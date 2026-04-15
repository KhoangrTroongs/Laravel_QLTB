@extends('layouts.app')

@section('title', 'Tạo Phiếu Mượn')
@section('page-title', 'Tạo Phiếu Mượn Thiết Bị')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment-users.index') }}">Mượn Thiết Bị</a></li>
    <li class="breadcrumb-item active">Tạo Phiếu Mượn</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-handshake mr-2"></i>Form Mượn Thiết Bị</h3>
            </div>
            <form action="{{ route('equipment-users.store') }}" method="POST">
                @csrf
                <div class="card-body">
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
                        <label for="user_id">Nhân Viên Mượn <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                            <option value="">-- Chọn nhân viên --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    [{{ $user->employee_id }}] {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="equipment_id">Thiết Bị <span class="text-danger">*</span></label>
                        <select name="equipment_id" id="equipment_id" class="form-control @error('equipment_id') is-invalid @enderror">
                            <option value="">-- Chọn thiết bị --</option>
                            @foreach($equipment as $eq)
                                <option value="{{ $eq->id }}" {{ old('equipment_id') == $eq->id ? 'selected' : '' }}>
                                    {{ $eq->name }} ({{ $eq->model }})
                                </option>
                            @endforeach
                        </select>
                        @error('equipment_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ngaymuon">Ngày Mượn <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="ngaymuon" id="ngaymuon"
                                       class="form-control @error('ngaymuon') is-invalid @enderror"
                                       value="{{ old('ngaymuon', now()->format('Y-m-d\TH:i')) }}">
                                @error('ngaymuon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Trạng Thái <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Đang mượn</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Đã trả</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Ghi Chú</label>
                        <textarea name="description" id="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="3" placeholder="Ghi chú thêm về việc mượn thiết bị...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>Tạo Phiếu Mượn
                    </button>
                    <a href="{{ route('equipment-users.index') }}" class="btn btn-default ml-2">
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
                <p>Chỉ hiển thị nhân viên <strong>đang làm việc</strong> và thiết bị <strong>đang hoạt động</strong>.</p>
                <p><strong>Trạng thái:</strong></p>
                <ul>
                    <li><span class="badge badge-warning">Đang mượn</span> - NV chưa trả</li>
                    <li><span class="badge badge-success">Đã trả</span> - NV đã hoàn trả</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

