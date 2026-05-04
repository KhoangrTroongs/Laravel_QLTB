@extends('layouts.app')

@section('title', 'Quản Lý Nhân Viên')
@section('page-title', 'Quản Lý Nhân Viên')

@section('breadcrumb')
    <li class="breadcrumb-item active">Nhân Viên</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-users me-2"></i>Danh Sách Nhân Viên</h3>
        <div class="card-tools">
            <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-user-plus me-1"></i>Thêm Nhân Viên
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Search & Filter -->
        <form method="GET" action="{{ route('users.index') }}" class="mb-4">
            <div class="row align-items-center g-3">
                <div class="col-md-7">
                    <div class="input-group shadow-sm">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên, mã NV, email, SĐT..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-warning px-3" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-control shadow-sm" onchange="this.form.submit()">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Đang làm việc</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Đã nghỉ việc</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('users.index') }}" class="btn btn-default shadow-sm border btn-block btn-input" title="Reset bộ lọc">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <x-sortable-header field="id" title="#" width="80" class="text-center" />
                    <x-sortable-header field="employee_id" title="Mã NV" />
                    <x-sortable-header field="name" title="Nhân Viên" />
                    <th class="py-3">
                        <span class="text-muted fw-bold" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            Liên Hệ
                        </span>
                    </th>
                    <x-sortable-header field="status" title="Trạng Thái" width="150" />
                    <th width="120" class="text-center py-3">
                        <span class="text-muted fw-bold" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            Thao Tác
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="text-center fw-bold">{{ $user->id }}</td>
                    <td>
                        <span class="badge text-bg-secondary border fw-bold" style="font-size: 0.9rem;">
                            {{ $user->employee_id }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($user->avatar)
                                @php
                                    $avatarUrl = str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar);
                                @endphp
                                <img src="{{ $avatarUrl }}" 
                                     class="rounded-circle me-3 border shadow-sm" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" 
                                     class="rounded-circle me-3 border shadow-sm" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            @endif
                            <div class="fw-bold">{{ $user->name }}</div>
                        </div>
                    </td>
                    <td>
                        <div><i class="far fa-envelope me-1 text-primary"></i>{{ $user->email }}</div>
                        <small class="text-muted"><i class="fas fa-phone me-1 text-success"></i>{{ $user->phone ?? 'N/A' }}</small>
                    </td>
                    <td>
                        @if($user->status == 1)
                            <span class="badge text-bg-success" style="border-radius: 8px; padding: 0.4rem 0.8rem;">
                                <i class="fas fa-check-circle me-1"></i>Đang làm việc
                            </span>
                        @else
                            <span class="badge text-bg-secondary" style="border-radius: 8px; padding: 0.4rem 0.8rem;">
                                <i class="fas fa-user-slash me-1"></i>Đã nghỉ việc
                            </span>
                        @endif
                    </td>
                    <td class="align-middle text-center">
                        <div class="btn-group">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-info text-white" title="Chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="submit" form="delete-form-{{ $user->id }}" class="btn btn-danger" title="Xóa bỏ">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" class="d-none delete-form">
                            @csrf @method('DELETE')
                        </form>
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
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">Hiển thị {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} / {{ $users->total() }} bản ghi</small>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

