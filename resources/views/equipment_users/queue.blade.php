@extends('layouts.app')

@section('title', 'Hàng Đợi Duyệt')
@section('page-title', 'Hàng Đợi Phê Duyệt')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment-users.index') }}">Mượn Thiết Bị</a></li>
    <li class="breadcrumb-item active">Hàng Đợi</li>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title fw-bold">
                    <i class="fas fa-hourglass-half me-2 text-indigo"></i>Phiếu mượn đang chờ xử lý 
                    <span class="badge text-bg-primary border ms-2">{{ $queue->count() }}</span>
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase fw-bold">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Nhân Viên</th>
                                <th>Thiết Bị</th>
                                <th>Thời Gian Yêu Cầu</th>
                                <th>Hạn Trả Đề Xuất</th>
                                <th class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($queue as $item)
                            <tr>
                                <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->user->avatar)
                                            <img src="{{ str_starts_with($item->user->avatar, 'http') ? $item->user->avatar : asset('storage/' . $item->user->avatar) }}" 
                                                 class="rounded-circle me-2 border" style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($item->user->name) }}&background=6610f2&color=fff" 
                                                 class="rounded-circle me-2 border" style="width: 32px; height: 32px;">
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $item->user->name }}</div>
                                            <div class="small text-muted">{{ $item->user->employee_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $item->equipment->name }}</div>
                                    <div class="small text-indigo fw-bold">{{ $item->equipment->model }}</div>
                                </td>
                                <td>
                                    <div><i class="far fa-calendar-alt me-1"></i>{{ \Carbon\Carbon::parse($item->ngaymuon)->format('d/m/Y') }}</div>
                                    <div class="small text-muted"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <div class="text-danger fw-bold">
                                        <i class="fas fa-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($item->hantra)->format('d/m/Y') }}
                                    </div>
                                    @if(\Carbon\Carbon::parse($item->hantra)->isPast())
                                        <span class="badge text-bg-danger">Sắp quá hạn</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group">
                                        <button type="submit" form="approve-form-{{ $item->id }}" class="btn btn-success" title="Duyệt"><i class="fas fa-check"></i> Duyệt</button>
                                        <button type="submit" form="reject-form-{{ $item->id }}" class="btn btn-danger" title="Từ chối"><i class="fas fa-times"></i> Từ chối</button>
                                    </div>
                                    <form id="approve-form-{{ $item->id }}" action="{{ route('equipment-users.approve', $item) }}" method="POST" class="d-none">@csrf @method('PATCH')</form>
                                    <form id="reject-form-{{ $item->id }}" action="{{ route('equipment-users.reject', $item) }}" method="POST" class="d-none">@csrf @method('PATCH')</form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="mb-3 opacity-25">
                                        <i class="fas fa-tasks fa-4x text-muted"></i>
                                    </div>
                                    <h5 class="text-muted">Hàng đợi trống</h5>
                                    <p class="text-muted small">Tất cả các yêu cầu mượn thiết bị đã được xử lý hoặc chưa có yêu cầu mới.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                 <p class="text-muted small mb-0 fst-italic">
                    <i class="fas fa-info-circle me-1"></i> Các yêu cầu có hạn trả nhỏ hơn ngày hiện tại sẽ được hệ thống dọn dẹp khi bạn tải trang này.
                 </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    if (typeof window.Echo !== 'undefined') {
        window.Echo.private(`App.Models.User.{{ auth()->id() }}`)
            .notification((notification) => {
                if (notification.type === 'App\\Notifications\\NewBorrowRequest') {
                    // Update Header Badge
                    const badge = $('.card-header .badge');
                    let count = parseInt(badge.text() || 0);
                    badge.text(count + 1);

                    // Remove Empty State if exists
                    const tableBody = $('tbody');
                    const emptyState = tableBody.find('td[colspan="6"]').closest('tr');
                    if (emptyState.length) emptyState.remove();

                    // Add New Row
                    const avatarUrl = notification.user_avatar 
                        ? (notification.user_avatar.startsWith('http') ? notification.user_avatar : `/storage/${notification.user_avatar}`)
                        : `https://ui-avatars.com/api/?name=${encodeURIComponent(notification.user_name)}&background=6610f2&color=fff`;

                    const approveUrl = `{{ url('equipment-users') }}/${notification.record_id}/approve`;
                    const rejectUrl = `{{ url('equipment-users') }}/${notification.record_id}/reject`;

                    let hantraFormatted = 'N/A';
                    if (notification.hantra) {
                        const date = new Date(notification.hantra);
                        hantraFormatted = date.toLocaleDateString('vi-VN');
                    }

                    const rowCount = tableBody.find('tr').length + 1;
                    
                    const newRow = `
                        <tr class="animate__animated animate__fadeInDown" style="background-color: #f5f3ff;">
                            <td class="ps-4 text-muted">${rowCount}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="${avatarUrl}" class="rounded-circle me-2 border" style="width: 32px; height: 32px; object-fit: cover;">
                                    <div>
                                        <div class="fw-bold text-dark">${notification.user_name}</div>
                                        <div class="small text-muted">${notification.employee_id}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">${notification.equipment_name}</div>
                                <div class="small text-indigo fw-bold">${notification.equipment_model}</div>
                            </td>
                            <td>
                                <div class="text-dark"><i class="far fa-calendar-alt me-1"></i>Hôm nay</div>
                                <div class="small text-muted"><i class="far fa-clock me-1"></i>vừa xong</div>
                            </td>
                            <td>
                                <div class="text-danger fw-bold">
                                    <i class="fas fa-calendar-check me-1"></i>${hantraFormatted}
                                </div>
                            </td>
                            <td class="align-middle text-center">
                                <div class="btn-group">
                                    <button type="submit" form="approve-form-${notification.record_id}" class="btn btn-success" title="Duyệt"><i class="fas fa-check"></i> Duyệt</button>
                                    <button type="submit" form="reject-form-${notification.record_id}" class="btn btn-danger" title="Từ chối"><i class="fas fa-times"></i> Từ chối</button>
                                </div>
                                <form id="approve-form-${notification.record_id}" action="${approveUrl}" method="POST" class="d-none"><input type="hidden" name="_token" value="${Laravel.csrfToken}"><input type="hidden" name="_method" value="PATCH"></form>
                                <form id="reject-form-${notification.record_id}" action="${rejectUrl}" method="POST" class="d-none"><input type="hidden" name="_token" value="${Laravel.csrfToken}"><input type="hidden" name="_method" value="PATCH"></form>
                            </td>
                        </tr>
                    `;
                    tableBody.prepend(newRow);

                    setTimeout(() => {
                        tableBody.find('tr').first().css('background-color', 'transparent', 'important');
                    }, 5000);
                }
            });
    }
});
</script>
@endpush
@endsection
