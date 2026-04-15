@extends('layouts.app')

@section('title', 'Báo Cáo Mượn Thiết Bị')
@section('page-title', 'Báo Cáo Mượn Thiết Bị')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment-users.index') }}">Mượn Thiết Bị</a></li>
    <li class="breadcrumb-item active">Báo Cáo</li>
@endsection

@section('content')
<div class="card card-white shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title text-dark font-weight-bold">
                <i class="fas fa-file-invoice-dollar mr-2 text-danger"></i>Thống Kê Mượn Thiết Bị Trong Năm {{ $year }}
            </h3>
            <form action="{{ route('equipment-users.report') }}" method="GET" class="form-inline">
                <div class="input-group input-group-sm border rounded-pill px-2 bg-light">
                    <span class="input-group-text bg-transparent border-0 text-muted small font-weight-bold">CHỌN NĂM:</span>
                    <select name="year" class="form-control bg-transparent border-0 font-weight-bold text-primary" onchange="this.form.submit()">
                        @for($i = date('Y'); $i >= 2024; $i--)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body p-4">
        @forelse($reportData as $data)
            <div class="user-report-card mb-5 bg-white border rounded-lg shadow-sm overflow-hidden">
                <div class="card-user-header p-3 bg-light border-bottom d-flex align-items-center">
                    <div class="avatar-wrapper mr-3">
                        <img src="{{ $data['user']->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($data['user']->name) }}" 
                             class="rounded-circle border-white shadow-sm" style="width: 50px; height: 50px; border-width: 3px !important; object-fit: cover;">
                    </div>
                    <div>
                        <h5 class="mb-0 font-weight-bold text-dark">{{ $data['user']->name }}</h5>
                        <p class="mb-0 text-muted small"><i class="fas fa-id-badge mr-1"></i>{{ $data['user']->employee_id }} — <i class="fas fa-envelope mr-1"></i>{{ $data['user']->email }}</p>
                    </div>
                    <div class="ml-auto">
                        <span class="badge badge-primary-soft px-3 py-2" style="background: rgba(0,123,255,0.1); color: #007bff; border-radius: 20px;">
                            <i class="fas fa-box-open mr-1"></i>{{ count($data['items']) }} Thiết bị đã mượn
                        </span>
                    </div>
                </div>
                
                <div class="p-0">
                    <table class="table mb-0">
                        <thead class="bg-white">
                            <tr>
                                <th class="border-top-0 border-bottom text-muted small" width="80">STT</th>
                                <th class="border-top-0 border-bottom text-muted small">THIẾT BỊ</th>
                                <th class="border-top-0 border-bottom text-muted small text-center" width="120">SỐ LẦN</th>
                                <th class="border-top-0 border-bottom text-muted small">THỜI GIAN SỬ DỤNG</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['items'] as $index => $item)
                            <tr class="align-middle">
                                <td class="text-center font-weight-bold text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-sm mr-3 text-info"><i class="fas fa-laptop"></i></div>
                                        <div>
                                            <div class="font-weight-bold text-dark">{{ $item['equipment']->name }}</div>
                                            <small class="text-muted">{{ $item['equipment']->model }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info-soft" style="background: rgba(23,162,184,0.1); color: #17a2b8;">
                                        {{ $item['count'] }} lần
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center small text-muted">
                                        <div class="px-2 py-1 bg-light rounded shadow-xs">
                                            <i class="far fa-calendar-check mr-1 text-success"></i>{{ \Carbon\Carbon::parse($item['from'])->format('d/m/Y') }}
                                        </div>
                                        <i class="fas fa-caret-right mx-2 text-muted opacity-50"></i>
                                        <div class="px-2 py-1 bg-light rounded shadow-xs">
                                            <i class="far fa-calendar-times mr-1 text-danger"></i>{{ \Carbon\Carbon::parse($item['to'])->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-search fa-4x text-muted opacity-25"></i>
                </div>
                <h4 class="text-muted">Dữ liệu trống</h4>
                <p class="text-muted">Không tìm thấy bản ghi mượn thiết bị nào trong năm {{ $year }}.</p>
                <a href="{{ route('equipment-users.index') }}" class="btn btn-primary mt-3">Tạo phiếu mượn ngay</a>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
    .user-report-block:last-child {
        border-bottom: none !important;
        margin-bottom: 0 !important;
    }
</style>
@endpush
