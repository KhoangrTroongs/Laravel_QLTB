@extends('layouts.app')
@section('title', 'Quản Lý Vai Trò')
@section('page-title', 'Phân Quyền Vai Trò')

@section('breadcrumb')
    <li class="breadcrumb-item active">Vai Trò</li>
@endsection

@section('content')
<!-- Tổng quan vai trò -->
<div class="row mb-4">
    @foreach($roles as $role)
        <div class="col-md-4">
            <div class="info-box" style="border-radius: 16px; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <span class="info-box-icon {{ $role->name === 'admin' ? 'bg-danger' : ($role->name === 'editor' ? 'bg-primary' : 'bg-secondary') }}"
                      style="border-radius: 16px 0 0 16px;">
                    <i class="fas {{ $role->name === 'admin' ? 'fa-shield-alt' : ($role->name === 'editor' ? 'fa-edit' : 'fa-user') }}"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text fw-bold">{{ $role->display_name }}</span>
                    <span class="info-box-number">{{ $role->users_count }} người dùng</span>
                    <small class="text-muted">{{ $role->description }}</small>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Bảng phân quyền -->
<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-bold">
            <i class="fas fa-users-cog me-2 text-primary"></i> Danh Sách Người Dùng & Vai Trò
        </h6>
    </div>
    <div class="card-body">
        <!-- Search & Filter (Synchronized with users page) -->
        <form method="GET" action="{{ route('roles.index') }}" class="mb-4">
            <div class="row align-items-center g-3">
                <div class="col-md-7">
                    <div class="input-group shadow-sm">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên, mã NV..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-warning px-3" type="submit"><i class="fas fa-search text-dark"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="role_id" class="form-control shadow-sm" onchange="this.form.submit()">
                        <option value="">-- Tất cả vai trò --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('roles.index') }}" class="btn btn-default shadow-sm border btn-block btn-input" title="Reset bộ lọc">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
            <thead>
                <tr>
                    <x-sortable-header field="id" title="#" width="100" />
                    <x-sortable-header field="name" title="Người Dùng" />
                    <x-sortable-header field="role" title="Vai Trò Hiện Tại" />
                    <th width="300" class="py-3 text-center">
                        <span class="text-muted fw-bold" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            Thay Đổi Vai Trò
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="align-middle ps-4 text-muted fw-bold">{{ $user->id }}</td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                @if($user->avatar)
                                    <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}"
                                         class="rounded-circle me-2" style="width: 38px; height: 38px; object-fit: cover;">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random"
                                         class="rounded-circle me-2" style="width: 38px; height: 38px;">
                                @endif
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->employee_id }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle text-center">
                            @forelse($user->roles as $role)
                                <span class="badge {{ $role->name === 'admin' ? 'text-bg-danger' : ($role->name === 'editor' ? 'text-bg-primary' : 'text-bg-secondary') }}"
                                      style="border-radius: 8px; padding: 0.4rem 0.8rem; font-weight: 700; font-size: 0.75rem;">
                                    {{ $role->display_name }}
                                </span>
                            @empty
                                <span class="text-muted small">Chưa có vai trò</span>
                            @endforelse
                        </td>
                        <td class="align-middle text-center">
                            @if($user->employee_id === 'ADMIN001')
                                <span class="text-muted small"><i class="fas fa-lock me-1"></i>Không thể thay đổi</span>
                            @else
                                <form action="{{ route('roles.assign', $user) }}" method="POST" class="d-flex justify-content-center align-items-center mb-0">
                                    @csrf
                                    <div class="d-flex align-items-center bg-light px-3 py-2 shadow-sm" style="border-radius: 12px;">
                                        @foreach($roles as $role)
                                            <div class="form-check form-check-inline me-4">
                                                <input type="radio" 
                                                       id="role_{{ $user->id }}_{{ $role->id }}" 
                                                       name="role_id" 
                                                       class="form-check-input" 
                                                       value="{{ $role->id }}"
                                                       {{ $user->roles->contains('id', $role->id) ? 'checked' : '' }}
                                                       onchange="this.form.submit()"
                                                       style="width: 1.5em; height: 1.5em; cursor: pointer;">
                                                <label class="form-check-label fw-bold d-flex align-items-center ms-1" 
                                                       for="role_{{ $user->id }}_{{ $role->id }}" 
                                                       style="cursor: pointer; height: 1.5em; font-size: 0.9rem;">
                                                    {{ $role->display_name }}
                                                </label>
                                            </div>
                                        @endforeach
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $users->links() }}
    </div>
</div>
@endsection
