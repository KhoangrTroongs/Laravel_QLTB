@extends('layouts.app')

@section('title', 'Bảng Điều Khiển')
@section('page-title', 'Bảng Quản Trị Trung Tâm')

@section('content')
<div class="container-fluid">
    
    <!-- ROW 1: LEGACY SMALL BOXES (TOP POSITION) -->
    <div class="row g-3">
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-info">
                <div class="inner">
                    <h3>{{ $equipmentCount }}</h3>
                    <p>Tổng Tài Sản</p>
                </div>
                <div class="small-box-icon">
                    <i class="fas fa-desktop"></i>
                </div>
                <a href="{{ route('equipment.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Quản lý kho <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>{{ $borrowingCount }}</h3>
                    <p>Đang Luân Chuyển</p>
                </div>
                <div class="small-box-icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <a href="{{ route('equipment-users.index', ['status' => 1]) }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-warning">
                <div class="inner">
                    <h3>{{ $pendingCount }}</h3>
                    <p>Yêu Cầu Chờ Duyệt</p>
                </div>
                <div class="small-box-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <a href="{{ route('equipment-users.queue') }}" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                    Xử lý ngay <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box text-bg-danger">
                <div class="inner">
                    <h3>{{ $overdueCount }}</h3>
                    <p>Cảnh Báo Quá Hạn</p>
                </div>
                <div class="small-box-icon">
                    <i class="fas fa-history"></i>
                </div>
                <a href="{{ route('equipment-users.index', ['overdue' => 1]) }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                    Kiểm tra vi phạm <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- ROW 2: ACTION CENTER (PENDING QUEUE) - COLORED HEADER -->
    <div class="row g-3">
        <div class="col-12">
            <div class="card shadow overflow-hidden border-0" style="border-radius: 12px;">
                <div class="card-header text-bg-warning bg-gradient py-3">
                    <h3 class="card-title fw-bold text-white">
                        <i class="fas fa-bolt me-2"></i> TRUNG TÂM PHÊ DUYỆT NHANH ({{ $pendingCount }})
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool text-white" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-valign-middle mb-0">
                            <thead>
                                <tr class="small text-uppercase">
                                    <th class="ps-4">Nhân Viên</th>
                                    <th>Thiết Bị</th>
                                    <th>Hạn Trả</th>
                                    <th>Lý Do Mượn</th>
                                    <th>Thời Gian</th>
                                    <th class="text-center">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingItems as $item)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            @if($item->user->avatar)
                                                <img src="{{ str_starts_with($item->user->avatar, 'http') ? $item->user->avatar : asset('storage/' . $item->user->avatar) }}" 
                                                     class="rounded-circle me-2 border shadow-sm" style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="avatar-sm me-2 bg-warning text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                    {{ substr($item->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold" style="font-size: 0.9rem;">{{ $item->user->name }}</div>
                                                <small class="text-muted">{{ $item->user->employee_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary" style="font-size: 0.9rem;">{{ $item->equipment->name }}</div>
                                        <div class="small text-muted fst-italic">{{ $item->equipment->model }}</div>
                                    </td>
                                    <td>
                                        <div class="text-danger fw-bold" style="font-size: 0.85rem;">
                                            <i class="fas fa-calendar-alt me-1"></i>{{ $item->hantra ? \Carbon\Carbon::parse($item->hantra)->format('d/m/Y') : 'Không có' }}
                                        </div>
                                    </td>
                                    <td style="max-width: 200px;">
                                        <div class="text-muted small text-truncate" title="{{ $item->description }}">
                                            <i class="fas fa-pen-nib me-1"></i>{{ $item->description ?: 'Không có ghi chú' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small text-muted">
                                            <i class="fas fa-clock me-1"></i>{{ $item->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group shadow-xs">
                                            <button type="submit" form="approve-form-{{ $item->id }}" class="btn btn-success px-3" title="Phê duyệt"><i class="fas fa-check"></i></button>
                                            <button type="submit" form="reject-form-{{ $item->id }}" class="btn btn-danger px-3" title="Từ chối"><i class="fas fa-times"></i></button>
                                        </div>
                                        <form id="approve-form-{{ $item->id }}" action="{{ route('equipment-users.approve', $item) }}" method="POST" class="d-none">@csrf @method('PATCH')</form>
                                        <form id="reject-form-{{ $item->id }}" action="{{ route('equipment-users.reject', $item) }}" method="POST" class="d-none">@csrf @method('PATCH')</form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-check-circle fa-3x text-success opacity-10 mb-3"></i>
                                        <p class="text-muted mb-0 fst-italic">Không có yêu cầu nào đang chờ xử lý.</p>
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

    <!-- ROW 3: ANALYTICS & TOOLS (COLORED HEADERS) -->
    <div class="row g-3">
        <!-- Analytics Area -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header text-bg-primary bg-gradient py-3">
                    <h3 class="card-title fw-bold text-white"><i class="fas fa-chart-line me-2"></i> XU HƯỚNG SỬ DỤNG TÀI SẢN</h3>
                </div>
                <div class="card-body">
                    <div style="height: 320px;">
                        <canvas id="pro-trends-chart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent System Feed -->
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header text-bg-success bg-gradient py-3">
                    <h3 class="card-title fw-bold text-white"><i class="fas fa-history me-2"></i> NHẬT KÝ MƯỢN TRẢ MỚI NHẤT</h3>
                </div>
                <div class="card-body p-0">
                    <div class="timeline m-0 p-4" style="max-height: 400px; overflow-y: auto;">
                        @foreach($latestRecords->take(6) as $record)
                        <div>
                            <i class="timeline-icon fas {{ $record->status == 3 ? 'fa-undo-alt text-bg-success' : 'fa-handshake text-bg-primary' }}"></i>
                            <div class="timeline-item">
                                <div class="timeline-header border-0 small fw-bold">
                                    {{ $record->user->name ?? 'N/A' }} 
                                    <span class="text-muted fw-normal">{{ $record->status == 3 ? 'đã trả' : 'đã mượn' }}</span>
                                    {{ $record->equipment->name ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Widgets -->
        <div class="col-lg-4">
            <!-- Stock Widget -->
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header text-bg-info bg-gradient py-3">
                    <h3 class="card-title fw-bold text-white"><i class="fas fa-th-list me-2"></i> TRẠNG THÁI TỒN KHO</h3>
                </div>
                <div class="card-body">
                    @foreach($categoryDistribution as $cat)
                    <div class="progress-group mb-3">
                        <span class="fw-semibold">{{ $cat->name }}</span>
                        <span class="float-end text-muted small"><b>{{ $cat->equipment_count }}</b> máy</span>
                        <div class="progress progress-xs" style="height: 6px;">
                            <div class="progress-bar {{ ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'][$loop->index % 5] }}" 
                                 style="width: {{ $equipmentCount > 0 ? ($cat->equipment_count / $equipmentCount) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="card-footer p-2 text-center text-primary fw-bold small">
                    TỈ LỆ KHẢ DỤNG: {{ round(($availableEquipmentCount/($equipmentCount?:1))*100) }}%
                </div>
            </div>

            <!-- Quick Action (New Design) -->
            <div class="card text-bg-dark bg-gradient border-0 shadow-lg mb-4" style="border-radius: 12px;">
                <div class="card-body p-4 text-center">
                    <h6 class="fw-bold mb-3 uppercase small">Trung Tâm Tác Vụ</h6>
                    <a href="{{ route('equipment.create') }}" class="btn btn-outline-light btn-block fw-bold mb-2">
                        <i class="fas fa-plus-circle me-2"></i> NHẬP THIẾT BỊ MỚI
                    </a>
                    <a href="{{ route('equipment-users.create') }}" class="btn btn-primary btn-block fw-bold shadow-sm">
                        <i class="fas fa-paper-plane me-2"></i> PHIẾU CẤP PHÁT
                    </a>
                </div>
            </div>

            <!-- To-Do Add-on -->
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header text-bg-secondary bg-gradient py-3">
                    <h3 class="card-title fw-bold text-white"><i class="fas fa-check-double me-2"></i> VIỆC CẦN LÀM</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="todo-list" data-widget="todo-list">
                        <li class="p-3 border-bottom">
                            <i class="fas fa-ellipsis-v text-muted me-2"></i>
                            <span class="text small">Gửi mail nhắc trả máy ({{ $overdueCount }})</span>
                            <small class="badge text-bg-danger ms-2">URGENT</small>
                        </li>
                        <li class="p-3">
                            <i class="fas fa-ellipsis-v text-muted me-2"></i>
                            <span class="text small text-muted fst-italic">Cấu hình thông báo Reverb</span>
                            <small class="badge text-bg-success ms-2">DONE</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f4f6f9; }
    .todo-list > li { border-start: 3px solid #007bff; border-radius: 0; }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Analytics Trends
        const trendsData = {!! json_encode($borrowingTrends) !!};
        const trendLabels = trendsData.map(d => {
            const date = new Date(d.date);
            return date.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit' });
        });
        const trendValues = trendsData.map(d => d.total);

        const ctxArea = document.getElementById('pro-trends-chart').getContext('2d');
        const gradient = ctxArea.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(0, 123, 255, 0.2)');
        gradient.addColorStop(1, 'rgba(0, 123, 255, 0)');

        if (ctxArea) {
            new Chart(ctxArea, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Sản lượng yêu cầu',
                        data: trendValues,
                        borderColor: '#007bff',
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#007bff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                        x: { grid: { display: false } }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // Real-time Approval Center Update
        if (typeof window.Echo !== 'undefined') {
            window.Echo.private(`App.Models.User.{{ auth()->id() }}`)
                .notification((notification) => {
                    if (notification.type === 'App\\Notifications\\NewBorrowRequest') {
                        // 1. Update Small Box & Card Header Count
                        const pendingBadges = $('.bg-warning h3, .text-bg-warning .card-title .badge');
                        pendingBadges.each(function() {
                            let text = $(this).text().replace(/[()]/g, '').trim();
                            let count = parseInt(text || 0);
                            $(this).text($(this).is('h3') ? count + 1 : `(${count + 1})`);
                        });

                        // 2. Add Row to Approval Table
                        const tableBody = $('.text-bg-warning').closest('.card').find('tbody');
                        const noNotif = tableBody.find('.opacity-10').closest('tr');
                        if (noNotif.length) noNotif.remove();

                        const avatarHtml = notification.user_avatar 
                            ? `<img src="${notification.user_avatar.startsWith('http') ? notification.user_avatar : '/storage/' + notification.user_avatar}" class="rounded-circle me-2 border shadow-sm" style="width: 32px; height: 32px; object-fit: cover;">`
                            : `<div class="avatar-sm me-2 bg-warning text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 32px; height: 32px; font-size: 0.8rem;">${notification.user_name.charAt(0)}</div>`;

                        const approveUrl = `{{ url('equipment-users') }}/${notification.record_id}/approve`;
                        const rejectUrl = `{{ url('equipment-users') }}/${notification.record_id}/reject`;

                        let hantraFormatted = 'Không có';
                        if (notification.hantra) {
                            const date = new Date(notification.hantra);
                            hantraFormatted = date.toLocaleDateString('vi-VN');
                        }

                        const newRow = `
                            <tr class="animate__animated animate__fadeInDown" style="background-color: #fffbeb;">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        ${avatarHtml}
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.9rem;">${notification.user_name}</div>
                                            <small class="text-muted">${notification.employee_id}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary" style="font-size: 0.9rem;">${notification.equipment_name}</div>
                                    <div class="small text-muted fst-italic">${notification.equipment_model}</div>
                                </td>
                                <td>
                                    <div class="text-danger fw-bold" style="font-size: 0.85rem;">
                                        <i class="fas fa-calendar-alt me-1"></i>${hantraFormatted}
                                    </div>
                                </td>
                                <td style="max-width: 200px;">
                                    <div class="text-muted small text-truncate" title="${notification.description || 'Không có ghi chú'}">
                                        <i class="fas fa-pen-nib me-1"></i>${notification.description || 'Không có ghi chú'}
                                    </div>
                                </td>
                                <td>
                                    <div class="small text-muted">
                                        <i class="fas fa-clock me-1"></i>vừa xong
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group shadow-xs">
                                        <button type="submit" form="approve-form-${notification.record_id}" class="btn btn-success px-3" title="Phê duyệt"><i class="fas fa-check"></i></button>
                                        <button type="submit" form="reject-form-${notification.record_id}" class="btn btn-danger px-3" title="Từ chối"><i class="fas fa-times"></i></button>
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
