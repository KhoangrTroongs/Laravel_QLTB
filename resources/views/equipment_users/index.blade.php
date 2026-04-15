@extends('layouts.app')

@section('title', 'Mượn Thiết Bị')
@section('page-title', 'Quản Lý Mượn Thiết Bị')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mượn Thiết Bị</li>
@endsection

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-handshake mr-2"></i>Danh Sách Mượn Thiết Bị</h3>
        <div class="card-tools">
            <a href="{{ route('equipment-users.create') }}" class="btn btn-primary btn-sm mr-1 shadow-sm">
                <i class="fas fa-plus mr-1"></i>Tạo Phiếu Mượn
            </a>
            <a href="{{ route('equipment-users.report') }}" class="btn btn-info btn-sm shadow-sm">
                <i class="fas fa-chart-bar mr-1"></i>Báo Cáo
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Search & Filter -->
        <form method="GET" action="{{ route('equipment-users.index') }}" class="mb-4">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="small font-weight-bold text-muted">TÌM KIẾM</label>
                    <input type="text" name="search" class="form-control shadow-sm" placeholder="Tên NV, thiết bị..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="small font-weight-bold text-muted">TRẠNG THÁI</label>
                    <select name="status" class="form-control shadow-sm">
                        <option value="">Tất cả</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đang mượn</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đã trả</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="small font-weight-bold text-muted">TỪ NGÀY</label>
                    <input type="date" name="from_date" class="form-control shadow-sm" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-2">
                    <label class="small font-weight-bold text-muted">ĐẾN NGÀY</label>
                    <input type="date" name="to_date" class="form-control shadow-sm" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2">
                    <div class="btn-group w-100">
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

        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="80" class="text-center">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'direction' => request('sort') == 'id' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                            # {!! request('sort') == 'id' ? (request('direction') == 'asc' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort opacity-25"></i>' !!}
                        </a>
                    </th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                            Nhân Viên {!! request('sort') == 'name' ? (request('direction') == 'asc' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort opacity-25"></i>' !!}
                        </a>
                    </th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'equipment', 'direction' => request('sort') == 'equipment' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                            Thiết Bị {!! request('sort') == 'equipment' ? (request('direction') == 'asc' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort opacity-25"></i>' !!}
                        </a>
                    </th>
                    <th>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'date', 'direction' => request('sort') == 'date' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                            Thời Gian {!! request('sort') == 'date' ? (request('direction') == 'asc' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort opacity-25"></i>' !!}
                        </a>
                    </th>
                    <th width="150">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'direction' => request('sort') == 'status' && request('direction') == 'asc' ? 'desc' : 'asc']) }}">
                            Trạng Thái {!! request('sort') == 'status' ? (request('direction') == 'asc' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>') : '<i class="fas fa-sort opacity-25"></i>' !!}
                        </a>
                    </th>
                    <th width="120" class="text-center text-muted">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr>
                    <td class="text-center text-muted font-weight-bold">{{ $record->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $record->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($record->user->name ?? 'U') }}" 
                                 class="rounded-circle mr-2 border" 
                                 style="width: 35px; height: 35px; object-fit: cover;">
                            <div>
                                <div class="font-weight-bold text-dark">{{ $record->user->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $record->user->employee_id ?? '' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="font-weight-bold text-primary">{{ $record->equipment->name ?? 'N/A' }}</div>
                        <small class="text-muted">{{ $record->equipment->model ?? '' }}</small>
                    </td>
                    <td>
                        <div class="text-dark"><i class="far fa-calendar-alt mr-1 text-info"></i>{{ \Carbon\Carbon::parse($record->ngaymuon)->format('d/m/Y') }}</div>
                        <small class="text-muted"><i class="far fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($record->ngaymuon)->format('H:i') }}</small>
                    </td>
                    <td>
                        @if($record->status == 1)
                            <span class="badge badge-warning text-dark shadow-sm">
                                <i class="fas fa-hand-holding mr-1"></i>Đang mượn
                            </span>
                        @else
                            <span class="badge badge-success shadow-sm">
                                <i class="fas fa-undo mr-1"></i>Đã trả đồ
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('equipment-users.edit', $record) }}" class="btn btn-warning btn-xs shadow-sm me-1" title="Cập nhật">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('equipment-users.destroy', $record) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Xác nhận xóa phiếu mượn này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-xs shadow-sm" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fas fa-receipt fa-3x mb-3 opacity-25"></i>
                        <p>Chưa có lịch sử mượn thiết bị nào.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">Hiển thị {{ $records->firstItem() ?? 0 }} - {{ $records->lastItem() ?? 0 }} / {{ $records->total() }} bản ghi</small>
            {{ $records->links() }}
        </div>
    </div>
</div>
@endsection
