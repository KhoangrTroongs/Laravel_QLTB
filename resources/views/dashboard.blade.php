@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Tổng Quan Hệ Thống')

@section('content')
<!-- KPI Row -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info shadow">
            <div class="inner">
                <h3>{{ $userCount }}</h3>
                <p>Tổng Nhân Viên</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('users.index') }}" class="small-box-footer"> Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success shadow">
            <div class="inner">
                <h3>{{ $equipmentCount }}</h3>
                <p>Tổng Thiết Bị</p>
            </div>
            <div class="icon">
                <i class="fas fa-laptop"></i>
            </div>
            <a href="{{ route('equipment.index') }}" class="small-box-footer"> Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning shadow">
            <div class="inner">
                <h3 class="text-white">{{ $borrowingCount }}</h3>
                <p class="text-white">Đang Được Mượn</p>
            </div>
            <div class="icon">
                <i class="fas fa-handshake"></i>
            </div>
            <a href="{{ route('equipment-users.index', ['status' => 1]) }}" class="small-box-footer" style="color: rgba(255,255,255,0.8) !important;"> Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger shadow">
            <div class="inner">
                <h3>Phân Tích</h3>
                <p>Xem Báo Cáo</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <a href="{{ route('equipment-users.report') }}" class="small-box-footer"> Xem báo cáo <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left column: Latest Records -->
    <div class="col-md-8">
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header border-0">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-history mr-2 text-primary"></i>Hoạt Động Gần Đây
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <a href="{{ route('equipment-users.index') }}" class="btn btn-tool" title="Xem tất cả">
                        <i class="fas fa-link"></i>
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-valign-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Nhân Viên</th>
                                <th>Thiết Bị</th>
                                <th>Thời Gian</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestRecords as $record)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($record->user && $record->user->avatar)
                                            <img src="{{ str_starts_with($record->user->avatar, 'http') ? $record->user->avatar : asset('storage/' . $record->user->avatar) }}" 
                                                 class="rounded-circle mr-2 border shadow-xs" style="width: 30px; height: 30px; object-fit: cover;">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($record->user->name ?? 'N/A') }}&size=30" 
                                                 class="rounded-circle mr-2 border shadow-xs">
                                        @endif
                                        <span>
                                            {{ $record->user->name ?? 'N/A' }}
                                            @if($record->user && $record->user->trashed())
                                                <small class="text-danger ml-1">(Nghỉ)</small>
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-primary font-weight-bold">
                                        {{ $record->equipment->name ?? 'N/A' }}
                                        @if($record->equipment && $record->equipment->trashed())
                                            <small class="text-danger ml-1">(Huỷ)</small>
                                        @endif
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($record->ngaymuon)->diffForHumans() }}</td>
                                <td>
                                    @if($record->status == 1)
                                        <span class="badge bg-warning-gradient px-2 py-1" style="background: linear-gradient(45deg, #f39c12, #f1c40f); color: #fff;">Đang mượn</span>
                                    @else
                                        <span class="badge bg-success-gradient px-2 py-1" style="background: linear-gradient(45deg, #27ae60, #2ecc71); color: #fff;">Đã trả</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('equipment-users.index') }}" class="uppercase text-sm font-weight-bold">Xem toàn bộ lịch sử <i class="fas fa-chevron-right ml-1"></i></a>
            </div>
        </div>
    </div>

    <!-- Right column: Stats & Quick Actions -->
    <div class="col-md-4">
        <!-- Info boxes -->
        <div class="info-box shadow-sm mb-3 bg-white">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text font-weight-bold">Thiết Bị Sẵn Có</span>
                <span class="info-box-number h4">{{ $availableEquipmentCount }}</span>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: {{ $equipmentCount > 0 ? ($availableEquipmentCount/$equipmentCount)*100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <div class="info-box shadow-sm mb-3 bg-white">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock text-white"></i></span>
            <div class="info-box-content">
                <span class="info-box-text font-weight-bold">Tỉ Lệ Đang Sử Dụng</span>
                <span class="info-box-number h4">{{ $equipmentCount > 0 ? round(($borrowingCount/$equipmentCount)*100, 1) : 0 }}<small>%</small></span>
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-warning" style="width: {{ $equipmentCount > 0 ? ($borrowingCount/$equipmentCount)*100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card shadow-sm border-0 bg-primary-gradient" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
            <div class="card-header border-0 pb-0">
                <h3 class="card-title text-white font-weight-bold">Thao Tác Nhanh</h3>
            </div>
            <div class="card-body pt-3">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <a href="{{ route('equipment-users.create') }}" class="btn btn-app bg-white border-0 shadow-sm w-100 m-0 py-3" style="height: auto;">
                            <i class="fas fa-plus-circle text-primary"></i> 
                            <span class="text-dark font-weight-bold mt-2 d-inline-block">Tạo phiếu</span>
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="{{ route('users.create') }}" class="btn btn-app bg-white border-0 shadow-sm w-100 m-0 py-3" style="height: auto;">
                            <i class="fas fa-user-plus text-success"></i>
                            <span class="text-dark font-weight-bold mt-2 d-inline-block">Thêm NV</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('equipment.create') }}" class="btn btn-app bg-white border-0 shadow-sm w-100 m-0 py-3" style="height: auto;">
                            <i class="fas fa-laptop-medical text-danger"></i>
                            <span class="text-dark font-weight-bold mt-2 d-inline-block">Thêm TB</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('equipment-users.report') }}" class="btn btn-app bg-white border-0 shadow-sm w-100 m-0 py-3" style="height: auto;">
                            <i class="fas fa-file-pdf text-info"></i>
                            <span class="text-dark font-weight-bold mt-2 d-inline-block">Báo cáo</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary-gradient { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .btn-app:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important; color: #1e3c72; }
    .table-valign-middle td { vertical-align: middle !important; }
</style>
@endsection
