@extends('layouts.app')

@section('title', 'Quản Lý Nhân Viên')
@section('page-title', 'Quản Lý Nhân Viên')

@section('breadcrumb')
    <li class="breadcrumb-item active">Nhân Viên</li>
@endsection

@section('content')
<div class="card card-success card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-users mr-2"></i>Danh Sách Nhân Viên</h3>
        <div class="card-tools">
            <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-user-plus mr-1"></i>Thêm Nhân Viên
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Search & Filter -->
        <form method="GET" action="{{ route('users.index') }}" class="mb-4">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="input-group shadow-sm">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên, mã NV, email, SĐT..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-warning px-3" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <select name="status" class="form-control shadow-sm" onchange="this.form.submit()">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đang làm việc</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đã nghỉ việc</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('users.index') }}" class="btn btn-default shadow-sm border btn-block" title="Reset bộ lọc">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="60" class="text-center">#</th>
                    <th>Nhân Viên</th>
                    <th>Liên Hệ</th>
                    <th width="150">Trạng Thái</th>
                    <th width="120" class="text-center">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="text-center text-muted font-weight-bold">{{ $user->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($user->avatar)
                                @if(str_starts_with($user->avatar, 'http'))
                                    <img src="{{ $user->avatar }}" 
                                         class="rounded-circle mr-3 border shadow-sm" 
                                         style="width: 45px; height: 45px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                         class="rounded-circle mr-3 border shadow-sm" 
                                         style="width: 45px; height: 45px; object-fit: cover;">
                                @endif
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" 
                                     class="rounded-circle mr-3 border shadow-sm" 
                                     style="width: 45px; height: 45px; object-fit: cover;">
                            @endif
                            <div>
                                <div class="font-weight-bold text-dark">{{ $user->name }}</div>
                                <small class="text-muted"><i class="fas fa-id-card mr-1"></i>{{ $user->employee_id }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div><i class="far fa-envelope mr-1 text-primary"></i>{{ $user->email }}</div>
                        <small class="text-muted"><i class="fas fa-phone mr-1 text-success"></i>{{ $user->phone ?? 'N/A' }}</small>
                    </td>
                    <td>
                        @if($user->status == 1)
                            <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>Đang làm việc</span>
                        @else
                            <span class="badge badge-secondary"><i class="fas fa-user-slash mr-1"></i>Đã nghỉ việc</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-xs shadow-sm me-1" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Xác nhận xóa nhân viên này?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-xs shadow-sm" title="Xóa bỏ">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" style="width: 80px; opacity: 0.3;">
                        <p class="text-muted mt-3">Không tìm thấy dữ liệu nhân viên phù hợp.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">Hiển thị {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} / {{ $users->total() }} bản ghi</small>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

