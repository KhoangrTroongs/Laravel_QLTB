@extends('layouts.app')

@section('title', 'Chi Tiết Mượn Thiết Bị')
@section('page-title', 'Chi Tiết Phiếu Mượn')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment-users.index') }}">Mượn Thiết Bị</a></li>
    <li class="breadcrumb-item active">Chi Tiết</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title">Phiếu mượn #{{ $equipmentUser->id }}</h3>
                <div class="card-tools">
                    <a href="{{ route('equipment-users.edit', $equipmentUser) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Sửa
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <h6><strong><i class="fas fa-user mr-2"></i>Nhân Viên</strong></h6>
                        <div class="p-2 bg-light rounded">
                            <p class="mb-1"><strong>Tên:</strong> {{ $equipmentUser->user->name }}</p>
                            <p class="mb-1"><strong>Mã NV:</strong> {{ $equipmentUser->user->employee_id }}</p>
                            <p class="mb-0"><strong>Email:</strong> {{ $equipmentUser->user->email }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6><strong><i class="fas fa-laptop mr-2"></i>Thiết Bị</strong></h6>
                        <div class="p-2 bg-light rounded">
                            <p class="mb-1"><strong>Tên:</strong> {{ $equipmentUser->equipment->name }}</p>
                            <p class="mb-1"><strong>Model:</strong> {{ $equipmentUser->equipment->model }}</p>
                            <p class="mb-0"><strong>Mô tả TB:</strong> {{ $equipmentUser->equipment->description ?? 'Không có' }}</p>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row mt-3">
                    <div class="col-md-4 text-center">
                        <p class="text-muted mb-1">Ngày mượn</p>
                        <h5>{{ \Carbon\Carbon::parse($equipmentUser->ngaymuon)->format('d/m/Y H:i') }}</h5>
                    </div>
                    <div class="col-md-4 text-center">
                        <p class="text-muted mb-1">Trạng thái</p>
                        @if($equipmentUser->status == 1)
                            <span class="badge badge-warning p-2">ĐANG MƯỢN</span>
                        @else
                            <span class="badge badge-success p-2">ĐÃ TRẢ</span>
                        @endif
                    </div>
                    <div class="col-md-4 text-center">
                        <p class="text-muted mb-1">Ghi chú</p>
                        <p>{{ $equipmentUser->description ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('equipment-users.index') }}" class="btn btn-default">Quay lại danh sách</a>
            </div>
        </div>
    </div>
</div>
@endsection
