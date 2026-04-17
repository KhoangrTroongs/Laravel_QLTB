@extends('layouts.app')

@section('title', 'Bảng Điều Khiển')
@section('page-title', 'Bảng Quản Trị Trung Tâm')

@section('content')
<div class="container-fluid">
    
    <!-- ROW 1: LEGACY SMALL BOXES (TOP POSITION) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info shadow-sm">
                <div class="inner">
                    <h3>{{ $equipmentCount }}</h3>
                    <p>Tổng Tài Sản</p>
                </div>
                <div class="icon"><i class="fas fa-desktop"></i></div>
                <a href="{{ route('equipment.index') }}" class="small-box-footer">Quản lý kho <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow-sm">
                <div class="inner">
                    <h3>{{ $borrowingCount }}</h3>
                    <p>Đang Luân Chuyển</p>
                </div>
                <div class="icon"><i class="fas fa-shipping-fast"></i></div>
                <a href="{{ route('equipment-users.index', ['status' => 1]) }}" class="small-box-footer">Xem chi tiết <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning shadow-sm">
                <div class="inner">
                    <h3 class="text-white">{{ $pendingCount }}</h3>
                    <p class="text-white">Yêu Cầu Chờ Duyệt</p>
                </div>
                <div class="icon"><i class="fas fa-user-clock"></i></div>
                <a href="{{ route('equipment-users.queue') }}" class="small-box-footer">Xử lý ngay <i class="fas fa-arrow-circle-right text-white"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger shadow-sm">
                <div class="inner">
                    <h3>{{ $overdueCount }}</h3>
                    <p>Cảnh Báo Quá Hạn</p>
                </div>
                <div class="icon"><i class="fas fa-history"></i></div>
                <a href="{{ route('equipment-users.index', ['overdue' => 1]) }}" class="small-box-footer">Kiểm tra vi phạm <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <!-- ROW 2: ACTION CENTER (PENDING QUEUE) - COLORED HEADER -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-md overflow-hidden border-0" style="border-radius: 12px;">
                <div class="card-header bg-gradient-warning py-3">
                    <h3 class="card-title font-weight-bold text-white">
                        <i class="fas fa-bolt mr-2"></i> TRUNG TÂM PHÊ DUYỆT NHANH ({{ $pendingCount }})
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool text-white" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-valign-middle mb-0">
                            <thead class="bg-light">
                                <tr class="text-muted small uppercase">
                                    <th class="pl-4">Nhân Viên</th>
                                    <th>Thiết Bị Yêu Cầu</th>
                                    <th>Model</th>
                                    <th class="text-center">Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingItems as $item)
                                <tr>
                                    <td class="pl-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm mr-3 bg-warning text-white rounded-circle d-flex align-items-center justify-content-center font-weight-bold shadow-sm" style="width: 32px; height: 32px;">
                                                {{ substr($item->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark">{{ $item->user->name }}</div>
                                                <small class="text-muted">{{ $item->user->employee_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="font-weight-bold text-primary">{{ $item->equipment->name }}</span></td>
                                    <td><span class="text-muted small">{{ $item->equipment->model }}</span></td>
                                    <td class="text-center">
                                        <div class="btn-group shadow-xs">
                                            <form action="{{ route('equipment-users.approve', $item) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm px-3 shadow-sm font-weight-bold">DUYỆT</button>
                                            </form>
                                            <form action="{{ route('equipment-users.reject', $item) }}" method="POST" class="ml-1">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-danger btn-sm px-3 shadow-sm font-weight-bold">HỦY</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="fas fa-check-circle fa-3x text-success opacity-10 mb-3"></i>
                                        <p class="text-muted mb-0 font-italic">Không có yêu cầu nào đang chờ xử lý.</p>
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
    <div class="row">
        <!-- Analytics Area -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-gradient-primary py-3">
                    <h3 class="card-title font-weight-bold text-white"><i class="fas fa-chart-line mr-2"></i> XU HƯỚNG SỬ DỤNG TÀI SẢN</h3>
                </div>
                <div class="card-body">
                    <div style="height: 320px;">
                        <canvas id="pro-trends-chart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent System Feed -->
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-gradient-success py-3">
                    <h3 class="card-title font-weight-bold text-white"><i class="fas fa-history mr-2"></i> NHẬT KÝ MƯỢN TRẢ MỚI NHẤT</h3>
                </div>
                <div class="card-body p-0">
                    <div class="timeline m-0 p-4" style="max-height: 400px; overflow-y: auto;">
                        @foreach($latestRecords->take(6) as $record)
                        <div class="mb-4">
                            <i class="fas {{ $record->status == 3 ? 'fa-undo-alt bg-success' : 'fa-handshake bg-primary' }} text-white shadow-sm" style="font-size: 11px;"></i>
                            <div class="timeline-item bg-light border-0 shadow-none py-2 px-3" style="margin-left: 55px; border-radius: 10px;">
                                <span class="time small text-muted font-weight-bold"><i class="fas fa-clock mr-1"></i>{{ $record->created_at->diffForHumans() }}</span>
                                <div class="timeline-header border-0 p-0 text-dark small font-weight-bold">
                                    {{ $record->user->name ?? 'N/A' }} 
                                    <span class="text-muted font-weight-normal">{{ $record->status == 3 ? 'đã trả' : 'đã mượn' }}</span>
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
                <div class="card-header bg-gradient-info py-3">
                    <h3 class="card-title font-weight-bold text-white"><i class="fas fa-th-list mr-2"></i> TRẠNG THÁI TỒN KHO</h3>
                </div>
                <div class="card-body">
                    @foreach($categoryDistribution as $cat)
                    <div class="progress-group mb-3">
                        <span class="font-weight-600">{{ $cat->name }}</span>
                        <span class="float-right text-muted small"><b>{{ $cat->equipment_count }}</b> máy</span>
                        <div class="progress progress-xs" style="height: 6px;">
                            <div class="progress-bar {{ ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger'][$loop->index % 5] }}" 
                                 style="width: {{ $equipmentCount > 0 ? ($cat->equipment_count / $equipmentCount) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="card-footer bg-light p-2 text-center text-primary font-weight-bold small">
                    TỈ LỆ KHẢ DỤNG: {{ round(($availableEquipmentCount/($equipmentCount?:1))*100) }}%
                </div>
            </div>

            <!-- Quick Action (New Design) -->
            <div class="card bg-gradient-dark border-0 shadow-lg text-white mb-4" style="border-radius: 12px;">
                <div class="card-body p-4 text-center">
                    <h6 class="font-weight-bold mb-3 uppercase small">Trung Tâm Tác Vụ</h6>
                    <a href="{{ route('equipment.create') }}" class="btn btn-outline-light btn-block font-weight-bold mb-2">
                        <i class="fas fa-plus-circle mr-2"></i> NHẬP THIẾT BỊ MỚI
                    </a>
                    <a href="{{ route('equipment-users.create') }}" class="btn btn-primary btn-block font-weight-bold shadow-sm">
                        <i class="fas fa-paper-plane mr-2"></i> PHIẾU CẤP PHÁT
                    </a>
                </div>
            </div>

            <!-- To-Do Add-on -->
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header bg-gradient-secondary py-3">
                    <h3 class="card-title font-weight-bold text-white"><i class="fas fa-check-double mr-2"></i> VIỆC CẦN LÀM</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="todo-list" data-widget="todo-list">
                        <li class="p-3 border-bottom">
                            <i class="fas fa-ellipsis-v text-muted mr-2"></i>
                            <span class="text small">Gửi mail nhắc trả máy ({{ $overdueCount }})</span>
                            <small class="badge badge-danger ml-2">URGENT</small>
                        </li>
                        <li class="p-3">
                            <i class="fas fa-ellipsis-v text-muted mr-2"></i>
                            <span class="text small text-muted font-italic">Cấu hình thông báo Reverb</span>
                            <small class="badge badge-success ml-2">DONE</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f4f6f9; }
    .bg-gradient-warning { background: linear-gradient(135deg, #f39c12, #e67e22) !important; }
    .bg-gradient-primary { background: linear-gradient(135deg, #007bff, #0056b3) !important; }
    .bg-gradient-success { background: linear-gradient(135deg, #28a745, #1e7e34) !important; }
    .bg-gradient-info { background: linear-gradient(135deg, #17a2b8, #117a8b) !important; }
    .bg-gradient-dark { background: linear-gradient(135deg, #343a40, #1d2124) !important; }
    
    .timeline::before { left: 24px !important; }
    .timeline > div > i { left: 10px !important; width: 30px !important; height: 30px !important; line-height: 30px !important; }
    .todo-list > li { border-left: 3px solid #007bff; border-radius: 0; }
    .small-box h3 { font-size: 2rem; }
    .shadow-md { box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .font-weight-600 { font-weight: 600; }
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
    });
</script>
@endpush
