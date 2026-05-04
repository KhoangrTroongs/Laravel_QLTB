@extends('layouts.app')

@section('title', 'Mượn Thiết Bị')
@section('page-title', 'Quản Lý Mượn Thiết Bị')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mượn Thiết Bị</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-handshake me-2"></i>Danh Sách Mượn Thiết Bị</h3>
        <div class="card-tools">
            <a href="{{ route('equipment-users.create') }}" class="btn btn-primary btn-sm me-1 shadow-sm">
                <i class="fas fa-plus me-1"></i>Tạo Phiếu Mượn
            </a>
            <a href="{{ route('equipment-users.report') }}" class="btn btn-info btn-sm shadow-sm">
                <i class="fas fa-chart-bar me-1"></i>Báo Cáo
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Search & Filter -->
        <form method="GET" action="{{ route('equipment-users.index') }}" class="mb-4">
            <div class="row align-items-end g-3">
                <div class="col-md-3">
                    <label class="small fw-bold text-muted">TÌM KIẾM</label>
                    <input type="text" name="search" class="form-control shadow-sm" placeholder="Tên NV, thiết bị..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted">TRẠNG THÁI</label>
                    <select name="status" class="form-control shadow-sm">
                        <option value="">Tất cả</option>
                        <option value="{{ \App\Models\EquipmentUser::STATUS_PENDING }}" {{ request('status') === (string)\App\Models\EquipmentUser::STATUS_PENDING ? 'selected' : '' }}>Chờ duyệt</option>
                        <option value="{{ \App\Models\EquipmentUser::STATUS_BORROWING }}" {{ request('status') === (string)\App\Models\EquipmentUser::STATUS_BORROWING ? 'selected' : '' }}>Đang mượn</option>
                        <option value="{{ \App\Models\EquipmentUser::STATUS_REJECTED }}" {{ request('status') === (string)\App\Models\EquipmentUser::STATUS_REJECTED ? 'selected' : '' }}>Từ chối</option>
                        <option value="{{ \App\Models\EquipmentUser::STATUS_RETURNED }}" {{ request('status') === (string)\App\Models\EquipmentUser::STATUS_RETURNED ? 'selected' : '' }}>Đã trả</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted d-block">LỌC NHANH</label>
                    <a href="{{ route('equipment-users.index', array_merge(request()->except('overdue'), ['overdue' => request('overdue') == 1 ? 0 : 1])) }}" 
                       class="btn {{ request('overdue') == 1 ? 'btn-danger' : 'btn-outline-danger' }} w-100 shadow-sm">
                        <i class="fas fa-exclamation-triangle me-1"></i>Trễ hạn
                    </a>
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted">TỪ NGÀY</label>
                    <input type="date" name="from_date" class="form-control shadow-sm" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="small fw-bold text-muted">ĐẾN NGÀY</label>
                    <input type="date" name="to_date" class="form-control shadow-sm" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-1">
                    <div class="btn-group w-25">
                        <button type="submit" class="btn btn-primary shadow-sm" title="Lọc dữ liệu">
                            <i class="fas fa-filter"></i>
                        </button>
                        <a href="{{ route('equipment-users.index') }}" class="btn btn-default shadow-sm border" title="Reset">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="borrow-requests-table">
            <thead>
                <tr>
                    <x-sortable-header field="id" title="#" width="80" class="text-center" />
                    <x-sortable-header field="name" title="Nhân Viên" />
                    <x-sortable-header field="equipment" title="Thiết Bị" />
                    <x-sortable-header field="date" title="Thời Gian" />
                    <x-sortable-header field="status" title="Trạng Thái" />
                    <th width="120" class="text-center py-3">
                        <span class="text-muted fw-bold" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            Thao Tác
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr id="record-row-{{ $record->id }}">
                    <td class="text-center fw-bold">{{ $record->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($record->user && $record->user->avatar)
                                <img src="{{ str_starts_with($record->user->avatar, 'http') ? $record->user->avatar : asset('storage/' . $record->user->avatar) }}" 
                                     class="rounded-circle me-2 border" 
                                     style="width: 35px; height: 35px; object-fit: cover;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($record->user->name ?? 'N/A') }}&background=random" 
                                     class="rounded-circle me-2 border" 
                                     style="width: 35px; height: 35px; object-fit: cover;">
                            @endif
                            <div>
                                <div class="fw-bold">
                                    {{ $record->user->name ?? 'N/A' }}
                                    @if($record->user && $record->user->trashed())
                                        <span class="badge text-bg-secondary p-1 ms-1" style="font-size: 0.6rem;">Đã nghỉ</span>
                                    @endif
                                </div>
                                <small class="text-muted">{{ $record->user->employee_id ?? '' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-primary">
                            {{ $record->equipment->name ?? 'N/A' }}
                            @if($record->equipment && $record->equipment->trashed())
                                <span class="badge text-bg-secondary p-1 ms-1" style="font-size: 0.6rem;">Đã hủy</span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $record->equipment->model ?? '' }}</small>
                    </td>
                    <td>
                        <div><i class="far fa-calendar-alt me-1 text-info"></i>{{ \Carbon\Carbon::parse($record->ngaymuon)->format('d/m/Y') }}</div>
                        @if($record->status == \App\Models\EquipmentUser::STATUS_RETURNED && $record->ngaytra)
                            <div class="mt-1"><span class="badge text-bg-success px-2 py-1" style="font-size: 0.7rem;"><i class="fas fa-check-circle me-1"></i>Trả: {{ \Carbon\Carbon::parse($record->ngaytra)->format('d/m/Y') }}</span></div>
                        @elseif($record->hantra)
                            <small class="text-danger fw-bold"><i class="fas fa-hourglass-half me-1"></i>Hạn: {{ \Carbon\Carbon::parse($record->hantra)->format('d/m/Y') }}</small>
                        @endif
                        <div class="small text-muted"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($record->ngaymuon)->format('H:i') }}</div>
                    </td>
                    <td class="status-cell">
                        @php
                            $status = $record->status;
                            $badgeClass = 'secondary';
                            $statusText = 'Không xác định';
                            $icon = 'question';
                            
                            if ($status == \App\Models\EquipmentUser::STATUS_PENDING) {
                                $badgeClass = 'info';
                                $statusText = 'Chờ duyệt';
                                $icon = 'clock';
                            } elseif ($status == \App\Models\EquipmentUser::STATUS_BORROWING) {
                                $badgeClass = 'warning text-dark';
                                $statusText = 'Đang mượn';
                                $icon = 'hand-holding';
                            } elseif ($status == \App\Models\EquipmentUser::STATUS_REJECTED) {
                                $badgeClass = 'danger';
                                $statusText = 'Từ chối';
                                $icon = 'times-circle';
                            } elseif ($status == \App\Models\EquipmentUser::STATUS_RETURNED) {
                                $badgeClass = 'success';
                                $statusText = 'Đã trả';
                                $icon = 'check-circle';
                            }
                        @endphp
                        <span class="badge text-bg-{{ $badgeClass }} shadow-sm">
                            <i class="fas fa-{{ $icon }} me-1"></i>{{ $statusText }}
                        </span>
                        @if($status == \App\Models\EquipmentUser::STATUS_BORROWING && $record->hantra && $record->hantra < now())
                            <div class="mt-2 text-overdue">
                                <span class="badge text-bg-danger animate__animated animate__pulse animate__infinite">
                                    <i class="fas fa-exclamation-circle me-1"></i>QUÁ HẠN
                                </span>
                            </div>
                        @endif
                    </td>
                    <td class="align-middle action-cell text-end pe-4">
                        <div class="btn-group">
                            @if($status == \App\Models\EquipmentUser::STATUS_PENDING)
                                <button type="submit" form="approve-form-{{ $record->id }}" class="btn btn-success" title="Duyệt"><i class="fas fa-check"></i></button>
                                <button type="submit" form="reject-form-{{ $record->id }}" class="btn btn-danger" title="Từ chối"><i class="fas fa-times"></i></button>
                            @endif

                            @if($status == \App\Models\EquipmentUser::STATUS_BORROWING)
                                <button type="submit" form="return-form-{{ $record->id }}" class="btn btn-info text-white" title="Xác nhận trả"><i class="fas fa-undo"></i></button>
                            @endif

                            <a href="{{ route('equipment-users.show', $record) }}" class="btn btn-primary" title="Chi tiết"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('equipment-users.edit', $record) }}" class="btn btn-warning" title="Cập nhật"><i class="fas fa-edit"></i></a>
                            <button type="submit" form="delete-form-{{ $record->id }}" class="btn btn-danger" title="Xóa"><i class="fas fa-trash"></i></button>
                        </div>

                        @if($status == \App\Models\EquipmentUser::STATUS_PENDING)
                            <form id="approve-form-{{ $record->id }}" action="{{ route('equipment-users.approve', $record) }}" method="POST" class="d-none">@csrf @method('PATCH')</form>
                            <form id="reject-form-{{ $record->id }}" action="{{ route('equipment-users.reject', $record) }}" method="POST" class="d-none">@csrf @method('PATCH')</form>
                        @endif
                        @if($status == \App\Models\EquipmentUser::STATUS_BORROWING)
                            <form id="return-form-{{ $record->id }}" action="{{ route('equipment-users.return', $record) }}" method="POST" class="d-none">@csrf @method('PATCH')</form>
                        @endif
                        <form id="delete-form-{{ $record->id }}" action="{{ route('equipment-users.destroy', $record) }}" method="POST" class="d-none delete-form">@csrf @method('DELETE')</form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted empty-row">
                        <i class="fas fa-receipt fa-3x mb-3 opacity-25"></i>
                        <p>Chưa có lịch sử mượn thiết bị nào.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">Hiển thị {{ $records->firstItem() ?? 0 }} - {{ $records->lastItem() ?? 0 }} / {{ $records->total() }} bản ghi</small>
            {{ $records->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof window.Echo !== 'undefined') {
            window.Echo.private('App.Models.User.{{ auth()->id() }}')
                .notification((notification) => {
                    if (notification.type === 'App\\Notifications\\NewBorrowRequest') {
                        // Remove empty row if existence
                        $('.empty-row').btn-closest('tr').remove();

                        const tableBody = $('#borrow-requests-table tbody');
                        const showUrl = `{{ url('equipment-users') }}/${notification.record_id}`;
                        const editUrl = `{{ url('equipment-users') }}/${notification.record_id}/edit`;
                        const approveUrl = `{{ url('equipment-users') }}/${notification.record_id}/approve`;
                        const rejectUrl = `{{ url('equipment-users') }}/${notification.record_id}/reject`;
                        
                        const newRow = `
                            <tr id="record-row-${notification.record_id}" class="animate__animated animate__fadeInDown" style="background-color: #f0fdf4;">
                                <td class="text-center text-muted fw-bold">${notification.record_id}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(notification.user_name)}&background=random" 
                                             class="rounded-circle me-2 border" style="width: 35px; height: 35px; object-fit: cover;">
                                        <div>
                                            <div class="fw-bold text-dark">${notification.user_name}</div>
                                            <small class="text-muted">Mới gửi</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">${notification.equipment_name}</div>
                                </td>
                                <td>
                                    <div class="text-dark"><i class="far fa-calendar-alt me-1 text-info"></i>vừa xong</div>
                                </td>
                                <td>
                                    <span class="badge text-bg-info shadow-sm">
                                        <i class="fas fa-clock me-1"></i>Chờ duyệt
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <form action="${approveUrl}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-xs me-1" title="Duyệt"><i class="fas fa-check"></i></button>
                                        </form>
                                        <form action="${rejectUrl}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-xs me-1" title="Từ chối"><i class="fas fa-times"></i></button>
                                        </form>
                                        <a href="${showUrl}" class="btn btn-primary btn-xs me-1" title="Chi tiết"><i class="fas fa-eye"></i></a>
                                        <a href="${editUrl}" class="btn btn-warning btn-xs me-1" title="Cập nhật"><i class="fas fa-edit"></i></a>
                                    </div>
                                </td>
                            </tr>
                        `;
                        tableBody.prepend(newRow);
                        
                        // Flash effect
                        setTimeout(() => {
                            $(`#record-row-${notification.record_id}`).css('background-color', 'transparent');
                        }, 5000);
                    }
                });
        }
    });
</script>
@endpush
@endsection
