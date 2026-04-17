@extends('layouts.app')

@section('title', 'Sửa Phiếu Mượn')
@section('page-title', 'Sửa Phiếu Mượn Thiết Bị')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment-users.index') }}">Mượn Thiết Bị</a></li>
    <li class="breadcrumb-item active">Sửa Phiếu</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title">Thông Tin Phiếu Mượn: #{{ $equipmentUser->id }}</h3>
            </div>
            <form action="{{ route('equipment-users.update', $equipmentUser) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="user_id">Nhân Viên</label>
                        <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $equipmentUser->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->employee_id }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="equipment_id">Thiết Bị</label>
                        <select name="equipment_id" id="equipment_id" class="form-control @error('equipment_id') is-invalid @enderror">
                            @foreach($equipment as $item)
                                <option value="{{ $item->id }}" {{ old('equipment_id', $equipmentUser->equipment_id) == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }} - {{ $item->model }}
                                </option>
                            @endforeach
                        </select>
                        @error('equipment_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ngaymuon">Ngày Mượn</label>
                                <input type="datetime-local" name="ngaymuon" id="ngaymuon" class="form-control @error('ngaymuon') is-invalid @enderror" value="{{ old('ngaymuon', $equipmentUser->ngaymuon ? \Carbon\Carbon::parse($equipmentUser->ngaymuon)->format('Y-m-d\TH:i') : '') }}">
                                @error('ngaymuon')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="hantra">Hạn Trả</label>
                                <input type="datetime-local" name="hantra" id="hantra" class="form-control @error('hantra') is-invalid @enderror" value="{{ old('hantra', $equipmentUser->hantra ? \Carbon\Carbon::parse($equipmentUser->hantra)->format('Y-m-d\TH:i') : '') }}">
                                @error('hantra')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ngaytra">Ngày Trả</label>
                                <input type="datetime-local" name="ngaytra" id="ngaytra" class="form-control @error('ngaytra') is-invalid @enderror" value="{{ old('ngaytra', $equipmentUser->ngaytra ? \Carbon\Carbon::parse($equipmentUser->ngaytra)->format('Y-m-d\TH:i') : '') }}">
                                @error('ngaytra')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="status">Trạng Thái</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="0" {{ old('status', $equipmentUser->status) == 0 ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="1" {{ old('status', $equipmentUser->status) == 1 ? 'selected' : '' }}>Đang mượn</option>
                            <option value="2" {{ old('status', $equipmentUser->status) == 2 ? 'selected' : '' }}>Từ chối</option>
                            <option value="3" {{ old('status', $equipmentUser->status) == 3 ? 'selected' : '' }}>Đã trả</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Thông Tin Mô Tả / Ghi Chú Trả</label>
                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="VD: Trạng thái thiết bị khi trả...">{{ old('description', $equipmentUser->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save mr-1"></i> Cập Nhật Phiếu
                    </button>
                    <a href="{{ route('equipment-users.index') }}" class="btn btn-default float-right">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
