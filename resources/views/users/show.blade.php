@extends('layouts.app')

@section('title', 'Chi Tiết Nhân Viên')
@section('page-title', 'Hồ Sơ Nhân Viên')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Nhân Viên</a></li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Profile Image -->
        <div class="card card-primary card-outline shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body box-profile">
                <div class="text-center mb-4">
                    @if($user->avatar)
                        <img class="profile-user-img img-fluid img-circle border-4 shadow-sm" 
                             src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}" 
                             alt="User profile picture" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #fff;">
                    @else
                        <img class="profile-user-img img-fluid img-circle border-4 shadow-sm" 
                             src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=120&background=3b82f6&color=fff" 
                             alt="User profile picture" style="border: 4px solid #fff;">
                    @endif
                </div>

                <h3 class="profile-username text-center font-weight-bold mb-0">{{ $user->name }}</h3>
                <p class="text-muted text-center mb-4">{{ $user->employee_id }}</p>

                <div class="d-flex justify-content-around mb-4 text-center">
                    <div>
                        <h5 class="font-weight-bold mb-0">{{ $user->equipments->where('pivot.status', \App\Models\EquipmentUser::STATUS_BORROWING)->count() }}</h5>
                        <small class="text-muted">Đang giữ</small>
                    </div>
                    <div>
                        <h5 class="font-weight-bold mb-0">{{ $user->equipments->count() }}</h5>
                        <small class="text-muted">Tổng lần mượn</small>
                    </div>
                    <div>
                        @if($user->status == 1)
                            <span class="badge badge-success px-2 py-1">Active</span>
                        @else
                            <span class="badge badge-danger px-2 py-1">Inactive</span>
                        @endif
                    </div>
                </div>

                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-block shadow-sm py-2">
                    <i class="fas fa-user-edit mr-2"></i>Chỉnh sửa hồ sơ
                </a>
            </div>
        </div>

        <!-- About Me Box -->
        <div class="card shadow-sm mt-4 border-0" style="border-radius: 15px;">
            <div class="card-header border-0 bg-white pt-4">
                <h3 class="card-title font-weight-bold"><i class="fas fa-id-card mr-2 text-primary"></i>Chi tiết liên hệ</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small mb-1">Email</label>
                    <div class="font-weight-600">{{ $user->email }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small mb-1">Số điện thoại</label>
                    <div class="font-weight-600">{{ $user->phone ?? 'Chưa cập nhật' }}</div>
                </div>
                <div class="mb-0">
                    <label class="text-muted small mb-1">Địa chỉ</label>
                    <div class="font-weight-600">{{ $user->address ?? 'Chưa cập nhật' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-header border-0 bg-white pt-4 d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-stream mr-2 text-primary"></i>Lịch trình mượn thiết bị
                </h3>
                <a href="{{ route('equipment-users.create', ['user_id' => $user->id]) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3 mt-n2">
                    <i class="fas fa-plus mr-1"></i> Cấp máy mới
                </a>
            </div>
            <div class="card-body bg-light" style="max-height: 800px; overflow-y: auto; border-radius: 0 0 15px 15px;">
                <div class="timeline mt-2">
                    @forelse($user->equipments as $equipment)
                        @php
                            $status = $equipment->pivot->status;
                            $iconColor = 'bg-info';
                            $statusLabel = 'Chờ duyệt';
                            if($status == \App\Models\EquipmentUser::STATUS_BORROWING) { $iconColor = 'bg-warning shadow-sm'; $statusLabel = 'Đang mượn'; }
                            elseif($status == \App\Models\EquipmentUser::STATUS_REJECTED) { $iconColor = 'bg-danger'; $statusLabel = 'Đã từ chối'; }
                            elseif($status == \App\Models\EquipmentUser::STATUS_RETURNED) { $iconColor = 'bg-success shadow-sm'; $statusLabel = 'Đã trả'; }
                        @endphp
                        
                        <div>
                            <i class="fas {{ $status == 3 ? 'fa-check' : ($status == 1 ? 'fa-laptop' : ($status == 0 ? 'fa-clock' : 'fa-times')) }} {{ $iconColor }} text-white"></i>
                            <div class="timeline-item shadow-none border mb-4" style="border-radius: 12px; border-left: 4px solid {{ $status == 3 ? '#28a745' : ($status == 1 ? '#ffc107' : ($status == 2 ? '#dc3545' : '#17a2b8')) }} !important;">
                                <span class="time text-muted"><i class="fas fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($equipment->pivot->ngaymuon)->format('H:i d/m/Y') }}</span>
                                <h3 class="timeline-header font-weight-bold">
                                    <span class="badge {{ $iconColor }} mr-2" style="font-size: 0.75rem;">{{ $statusLabel }}</span>
                                    {{ $equipment->name }}
                                </h3>
                                <div class="timeline-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="text-muted small">Model</div>
                                            <div class="font-weight-bold text-indigo small">{{ $equipment->model }}</div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="text-muted small">Thời gian thuê</div>
                                            <div class="small">
                                                {{ \Carbon\Carbon::parse($equipment->pivot->ngaymuon)->format('d/m/Y') }} 
                                                <i class="fas fa-long-arrow-alt-right mx-1 text-muted"></i> 
                                                @if($equipment->pivot->ngaytra)
                                                    {{ \Carbon\Carbon::parse($equipment->pivot->ngaytra)->format('d/m/Y') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($equipment->pivot->hantra)->format('d/m/Y') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if($equipment->pivot->description)
                                        <div class="mt-2 p-2 bg-light rounded italic small">
                                            <i class="fas fa-comment-dots mr-1 opacity-50"></i>{{ $equipment->pivot->description }}
                                        </div>
                                    @endif
                                </div>
                                <div class="timeline-footer py-2">
                                    <a href="{{ route('equipment-users.show', $equipment->pivot->id) }}" class="btn btn-xs outline-primary">Chi tiết phiếu</a>
                                    <a href="{{ route('equipment.show', $equipment->id) }}" class="btn btn-xs text-muted ml-2">Thông tin máy</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted opacity-25 mb-3"></i>
                            <p class="text-muted">Chưa có lịch sử mượn trả thiết bị.</p>
                        </div>
                    @endforelse
                    <div>
                        <i class="fas fa-rocket bg-gray text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .font-weight-600 { font-weight: 600; }
    .timeline-item { background: #fff !important; }
    .border-4 { border-width: 4px !important; }
</style>
@endsection
