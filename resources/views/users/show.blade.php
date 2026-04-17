@extends('layouts.app')

@section('title', 'Chi Tiết Nhân Viên')
@section('page-title', 'Hồ Sơ Nhân Viên')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Nhân Viên</a></li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline shadow-sm border-0">
            <div class="card-body box-profile">
                <div class="text-center mb-3">
                    @if($user->avatar)
                        <img class="profile-user-img img-fluid img-circle border shadow-sm" 
                             src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}" 
                             alt="User profile picture" style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <img class="profile-user-img img-fluid img-circle border shadow-sm" 
                             src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=100&background=random" 
                             alt="User profile picture">
                    @endif
                </div>

                <h3 class="profile-username text-center font-weight-bold">{{ $user->name }}</h3>
                <p class="text-muted text-center">{{ $user->employee_id }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item border-0">
                        <b>Trạng thái</b> 
                        <span class="float-right badge {{ $user->status == 1 ? 'badge-success' : 'badge-danger' }} px-2">
                            {{ $user->status == 1 ? 'Đang làm việc' : 'Đã nghỉ việc' }}
                        </span>
                    </li>
                    <li class="list-group-item border-0">
                        <b>Thiết bị đang giữ</b> 
                        <span class="float-right badge badge-primary px-2">
                            {{ $user->equipments->where('pivot.status', \App\Models\EquipmentUser::STATUS_BORROWING)->count() }}
                        </span>
                    </li>
                    <li class="list-group-item border-0">
                        <b>Số lần mượn</b> 
                        <span class="float-right badge badge-info px-2">{{ $user->equipments->count() }}</span>
                    </li>
                </ul>

                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-block font-weight-bold">
                    <i class="fas fa-edit mr-1"></i> Chỉnh sửa hồ sơ
                </a>
            </div>
        </div>

        <!-- About Me Box -->
        <div class="card card-primary shadow-sm mt-4 border-0">
            <div class="card-header border-0 bg-white">
                <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-info-circle mr-2 text-primary"></i>Thông tin liên hệ</h3>
            </div>
            <div class="card-body">
                <strong><i class="fas fa-envelope mr-1"></i> Email</strong>
                <p class="text-muted">{{ $user->email }}</p>
                <hr>
                <strong><i class="fas fa-phone mr-1"></i> Số điện thoại</strong>
                <p class="text-muted">{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                <hr>
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Địa chỉ</strong>
                <p class="text-muted mb-0">{{ $user->address ?? 'Chưa cập nhật' }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="card card-outline card-info shadow-sm border-0">
            <div class="card-header border-0 bg-white d-flex align-items-center justify-content-between">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-history mr-2 text-info"></i>Lịch sử mượn thiết bị
                </h3>
                <a href="{{ route('equipment-users.create', ['user_id' => $user->id]) }}" class="btn btn-primary btn-sm rounded-pill px-3">
                    <i class="fas fa-plus mr-1"></i> Tạo phiếu mới
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light small font-weight-bold text-muted uppercase">
                            <tr>
                                <th class="pl-4 py-3">Thiết Bị</th>
                                <th class="py-3">Ngày Mượn</th>
                                <th class="py-3">Hạn Trả</th>
                                <th class="py-3 text-center">Trạng Thái</th>
                                <th class="py-3 pr-4 text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->equipments as $equipment)
                            <tr>
                                <td class="pl-4 py-3">
                                    <div class="font-weight-bold text-dark">{{ $equipment->name }}</div>
                                    <small class="text-muted">{{ $equipment->model }}</small>
                                </td>
                                <td class="py-3">
                                    <div class="small">{{ \Carbon\Carbon::parse($equipment->pivot->ngaymuon)->format('d/m/Y H:i') }}</div>
                                </td>
                                <td class="py-3">
                                    @if($equipment->pivot->hantra)
                                        <div class="small {{ $equipment->pivot->status == 1 && $equipment->pivot->hantra < now() ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                            {{ \Carbon\Carbon::parse($equipment->pivot->hantra)->format('d/m/Y') }}
                                        </div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    @php
                                        $status = $equipment->pivot->status;
                                        $badgeClass = 'info';
                                        $statusText = 'Chờ duyệt';
                                        if($status == 1) { $badgeClass = 'warning text-dark'; $statusText = 'Đang mượn'; }
                                        elseif($status == 2) { $badgeClass = 'danger'; $statusText = 'Từ chối'; }
                                        elseif($status == 3) { $badgeClass = 'success'; $statusText = 'Đã trả'; }
                                    @endphp
                                    <span class="badge badge-{{ $badgeClass }} px-2 py-1">{{ $statusText }}</span>
                                </td>
                                <td class="py-3 pr-4 text-center">
                                    <a href="{{ route('equipment-users.show', $equipment->pivot->id) }}" class="btn btn-outline-info btn-xs rounded-circle" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-receipt fa-2x mb-2 opacity-50"></i>
                                    <p class="mb-0">Nhân viên này chưa mượn thiết bị nào.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
