@extends('layouts.app')

@section('title', 'Hàng Đợi Duyệt')
@section('page-title', 'Hàng Đợi Phê Duyệt')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment-users.index') }}">Mượn Thiết Bị</a></li>
    <li class="breadcrumb-item active">Hàng Đợi</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-indigo card-outline shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-hourglass-half mr-2 text-indigo"></i>Phiếu mượn đang chờ xử lý 
                    <span class="badge badge-indigo border ml-2">{{ $queue->count() }}</span>
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase font-weight-bold">
                            <tr>
                                <th class="pl-4">#</th>
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
                                <td class="pl-4 text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->user->avatar)
                                            <img src="{{ str_starts_with($item->user->avatar, 'http') ? $item->user->avatar : asset('storage/' . $item->user->avatar) }}" 
                                                 class="rounded-circle mr-2 border" style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($item->user->name) }}&background=6610f2&color=fff" 
                                                 class="rounded-circle mr-2 border" style="width: 32px; height: 32px;">
                                        @endif
                                        <div>
                                            <div class="font-weight-bold text-dark">{{ $item->user->name }}</div>
                                            <div class="small text-muted">{{ $item->user->employee_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-weight-600 text-dark">{{ $item->equipment->name }}</div>
                                    <div class="small text-indigo font-weight-bold">{{ $item->equipment->model }}</div>
                                </td>
                                <td>
                                    <div class="text-dark"><i class="far fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::parse($item->ngaymuon)->format('d/m/Y') }}</div>
                                    <div class="small text-muted"><i class="far fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}</div>
                                </td>
                                <td>
                                    <div class="text-danger font-weight-bold">
                                        <i class="fas fa-calendar-check mr-1"></i>{{ \Carbon\Carbon::parse($item->hantra)->format('d/m/Y') }}
                                    </div>
                                    @if(\Carbon\Carbon::parse($item->hantra)->isPast())
                                        <span class="badge badge-danger">Sắp quá hạn</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <form action="{{ route('equipment-users.approve', $item) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm px-3 mr-1 shadow-xs">
                                                <i class="fas fa-check mr-1"></i> Duyệt
                                            </button>
                                        </form>
                                        <form action="{{ route('equipment-users.reject', $item) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm px-3 shadow-xs">
                                                <i class="fas fa-times mr-1"></i> Từ chối
                                            </button>
                                        </form>
                                    </div>
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
            <div class="card-footer bg-white text-right">
                 <p class="text-muted small mb-0 font-italic">
                    <i class="fas fa-info-circle mr-1"></i> Các yêu cầu có hạn trả nhỏ hơn ngày hiện tại sẽ được hệ thống dọn dẹp khi bạn tải trang này.
                 </p>
            </div>
        </div>
    </div>
</div>
@endsection
