@extends('layouts.app')

@section('title', 'Quản Lý Thiết Bị')
@section('page-title', 'Quản Lý Thiết Bị')

@section('breadcrumb')
    <li class="breadcrumb-item active">Thiết Bị</li>
@endsection

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-laptop mr-2"></i>Danh Sách Thiết Bị</h3>
        <div class="card-tools">
            <a href="{{ route('equipment.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i>Thêm Thiết Bị
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Search & Filter -->
        <form method="GET" action="{{ route('equipment.index') }}" class="mb-4">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="input-group shadow-sm">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm tên, model..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-warning px-3" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-control shadow-sm" onchange="this.form.submit()">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động bình thường</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Bị hư</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('equipment.index') }}" class="btn btn-default shadow-sm border btn-block btn-input" title="Reset bộ lọc">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <x-sortable-header field="id" title="#" width="80" class="text-center" />
                    <x-sortable-header field="name" title="Thiết Bị" />
                    <x-sortable-header field="model" title="Model & Phân Loại" />
                    <th class="py-3">
                        <span class="text-muted font-weight-bold" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            Mô Tả
                        </span>
                    </th>
                    <x-sortable-header field="status" title="Tình Trạng" width="180" />
                    <th width="120" class="text-center py-3">
                        <span class="text-muted font-weight-bold" style="font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase;">
                            Thao Tác
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipment as $item)
                <tr>
                    <td class="text-center text-muted font-weight-bold">{{ $item->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="mr-3 border rounded overflow-hidden shadow-sm" style="width: 50px; height: 35px; background: #f8f9fa;">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <i class="fas fa-laptop text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="font-weight-bold text-dark">{{ $item->name }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="badge badge-light border text-dark">{{ $item->model }}</div>
                    </td>
                    <td><small class="text-muted">{{ Str::limit($item->description ?? 'Không có mô tả', 50) }}</small></td>
                    <td>
                        @if($item->status == 1)
                            <span class="badge badge-success shadow-sm">
                                <i class="fas fa-check-circle mr-1"></i>Sẵn sàng sử dụng
                            </span>
                        @else
                            <span class="badge badge-danger shadow-sm">
                                <i class="fas fa-tools mr-1"></i>Đang bảo trì/Hư
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{ route('equipment.edit', $item) }}" class="btn btn-warning btn-xs shadow-sm me-1" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('equipment.destroy', $item) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-danger btn-xs shadow-sm confirm-delete" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fas fa-laptop-code fa-3x mb-3 opacity-25"></i>
                        <p>Kho thiết bị hiện đang trống.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">Hiển thị {{ $equipment->firstItem() ?? 0 }} - {{ $equipment->lastItem() ?? 0 }} / {{ $equipment->total() }} bản ghi</small>
            {{ $equipment->links() }}
        </div>
    </div>
</div>
@endsection

