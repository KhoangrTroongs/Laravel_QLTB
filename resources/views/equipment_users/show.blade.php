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
                            <p class="mb-1"><strong>Tên:</strong> {{ $equipmentUser->user->name ?? 'N/A' }} @if($equipmentUser->user?->trashed()) <small class="text-danger">(Đã nghỉ)</small> @endif</p>
                            <p class="mb-1"><strong>Mã NV:</strong> {{ $equipmentUser->user->employee_id ?? '-' }}</p>
                            <p class="mb-0"><strong>Email:</strong> {{ $equipmentUser->user->email ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6><strong><i class="fas fa-laptop mr-2"></i>Thiết Bị</strong></h6>
                        <div class="p-2 bg-light rounded">
                            <p class="mb-1"><strong>Tên:</strong> {{ $equipmentUser->equipment->name ?? 'N/A' }} @if($equipmentUser->equipment?->trashed()) <small class="text-danger">(Đã xoá)</small> @endif</p>
                            <p class="mb-1"><strong>Model:</strong> {{ $equipmentUser->equipment->model ?? '-' }}</p>
                            <p class="mb-0"><strong>Mô tả TB:</strong> {{ $equipmentUser->equipment->description ?? 'Không có' }}</p>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row mt-3">
                    <div class="col-md-3 text-center">
                        <p class="text-muted mb-1 small font-weight-bold">NGÀY MƯỢN</p>
                        <h6>{{ \Carbon\Carbon::parse($equipmentUser->ngaymuon)->format('d/m/Y H:i') }}</h6>
                    </div>
                    <div class="col-md-3 text-center border-left">
                        <p class="text-muted mb-1 small font-weight-bold">HẠN TRẢ</p>
                        <h6 class="{{ $equipmentUser->status == 1 && $equipmentUser->hantra < now() ? 'text-danger font-weight-bold' : '' }}">
                            {{ $equipmentUser->hantra ? \Carbon\Carbon::parse($equipmentUser->hantra)->format('d/m/Y') : 'Không có' }}
                        </h6>
                    </div>
                    @if($equipmentUser->status == \App\Models\EquipmentUser::STATUS_RETURNED)
                    <div class="col-md-3 text-center border-left">
                        <p class="text-muted mb-1 small font-weight-bold">NGÀY TRẢ THỰC TẾ</p>
                        <h6 class="text-success">{{ $equipmentUser->ngaytra ? \Carbon\Carbon::parse($equipmentUser->ngaytra)->format('d/m/Y H:i') : 'N/A' }}</h6>
                    </div>
                    @endif
                    <div class="col-md-{{ $equipmentUser->status == \App\Models\EquipmentUser::STATUS_RETURNED ? 3 : 6 }} text-center border-left">
                        <p class="text-muted mb-1 small font-weight-bold">TRẠNG THÁI</p>
                        @php
                            $status = $equipmentUser->status;
                            $badgeClass = 'secondary';
                            $statusText = 'KHÔNG XÁC ĐỊNH';
                            
                            if ($status == \App\Models\EquipmentUser::STATUS_PENDING) {
                                $badgeClass = 'info';
                                $statusText = 'CHỜ DUYỆT';
                            } elseif ($status == \App\Models\EquipmentUser::STATUS_BORROWING) {
                                $badgeClass = 'warning';
                                $statusText = 'ĐANG MƯỢN';
                            } elseif ($status == \App\Models\EquipmentUser::STATUS_REJECTED) {
                                $badgeClass = 'danger';
                                $statusText = 'TỪ CHỐI';
                            } elseif ($status == \App\Models\EquipmentUser::STATUS_RETURNED) {
                                $badgeClass = 'success';
                                $statusText = 'ĐÃ TRẢ';
                            }
                        @endphp
                        <span class="badge badge-{{ $badgeClass }} p-2">{{ $statusText }}</span>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-light rounded">
                    <p class="text-muted mb-1 small font-weight-bold"><i class="fas fa-comment-dots mr-2"></i>GHI CHÚ / MÔ TẢ PHIẾU</p>
                    <p class="mb-0">{{ $equipmentUser->description ?? 'Không có ghi chú.' }}</p>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('equipment-users.index') }}" class="btn btn-default">
                    <i class="fas fa-arrow-left mr-1"></i> Quay lại danh sách
                </a>

                <div class="float-right">
                    @if($equipmentUser->status == \App\Models\EquipmentUser::STATUS_PENDING)
                        <form action="{{ route('equipment-users.approve', $equipmentUser) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-success shadow-sm mr-1">
                                <i class="fas fa-check mr-1"></i> Duyệt phiếu
                            </button>
                        </form>
                        <form action="{{ route('equipment-users.reject', $equipmentUser) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-danger shadow-sm mr-1">
                                <i class="fas fa-times mr-1"></i> Từ chối
                            </button>
                        </form>
                    @endif

                    @if($equipmentUser->status == \App\Models\EquipmentUser::STATUS_BORROWING)
                        <form action="{{ route('equipment-users.return', $equipmentUser) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-info shadow-sm mr-1">
                                <i class="fas fa-undo mr-1"></i> Xác nhận trả
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('equipment-users.destroy', $equipmentUser) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger shadow-sm confirm-delete">
                            <i class="fas fa-trash mr-1"></i> Xóa
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
