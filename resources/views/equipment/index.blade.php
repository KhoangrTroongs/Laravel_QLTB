@extends('layouts.app')

@section('title', 'Quản Lý Thiết Bị')
@section('page-title', 'Quản Lý Thiết Bị')

@section('breadcrumb')
    <li class="breadcrumb-item active">Thiết Bị</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title fw-bold"><i class="fas fa-laptop me-2 text-primary"></i>Danh Sách Thiết Bị</h3>
        <div class="card-tools">
            <a href="{{ route('equipment.create') }}" class="btn btn-primary btn-sm shadow-sm px-3">
                <i class="fas fa-plus me-1"></i> Thêm Thiết Bị
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Search & Filter -->
        <form method="GET" action="{{ route('equipment.index') }}" class="mb-4">
            <div class="row align-items-center g-3">
                <div class="col-md-7">
                    <div class="input-group shadow-sm">
                        <input type="text" name="search" class="form-control border-end-0" placeholder="Tìm kiếm tên, model..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-warning px-4" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="status" class="form-control shadow-sm" onchange="this.form.submit()">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động bình thường</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Bị hư / Bảo trì</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <a href="{{ route('equipment.index') }}" class="btn btn-default shadow-sm border btn-block" title="Reset bộ lọc">
                        <i class="fas fa-undo"></i>
                    </a>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="bg-light">
                    <tr>
                        <x-sortable-header field="id" title="#" width="80" class="text-center" />
                        <x-sortable-header field="name" title="THIẾT BỊ" />
                        <x-sortable-header field="model" title="MODEL & LOẠI" />
                        <th>THÔNG SỐ / MÔ TẢ</th>
                        <x-sortable-header field="status" title="TÌNH TRẠNG" width="160" />
                        <x-sortable-header field="availability" title="KHẢ DỤNG" width="160" />
                        <th width="120" class="text-center">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipment as $item)
                    <tr>
                        <td class="text-center small fw-bold">{{ $item->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-3 border rounded overflow-hidden shadow-xs bg-body-secondary" style="width: 50px; height: 38px;">
                                    @if($item->image)
                                        <img src="{{ str_starts_with($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="fas fa-laptop text-muted opacity-50"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="fw-bold">{{ $item->name }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-bold mb-1">{{ $item->model }}</div>
                            <span class="badge text-bg-info shadow-xs" style="font-size: 0.7rem;">
                                <i class="fas fa-tag me-1"></i>{{ $item->category->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <div class="small text-muted mb-2">{{ Str::limit($item->description ?? 'Không có mô tả', 40) }}</div>
                            @if($item->spec)
                                <div class="d-flex flex-wrap" style="gap: 4px;">
                                    @foreach(array_slice($item->spec, 0, 3) as $key => $val)
                                        <span class="badge text-bg-secondary p-1" style="font-size: 0.65rem; font-weight: normal; border-radius: 4px;">
                                            {{ $key }}: {{ $val }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($item->status == 1)
                                <span class="badge text-bg-success shadow-xs px-2 py-1" style="border-radius: 6px;">
                                    <i class="fas fa-check-circle me-1"></i>TỐT
                                </span>
                            @else
                                <span class="badge text-bg-danger shadow-xs px-2 py-1" style="border-radius: 6px;">
                                    <i class="fas fa-tools me-1"></i>HỎNG
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($item->status == 1 && $item->active_borrow_count == 0 && $item->available == 1)
                                <span class="badge text-bg-primary shadow-xs px-2 py-1" style="border-radius: 6px;">
                                    <i class="fas fa-handshake me-1"></i>SẴN SÀNG
                                </span>
                            @else
                                <span class="badge text-bg-secondary shadow-xs px-2 py-1" style="border-radius: 6px;">
                                    <i class="fas fa-clock me-1"></i>ĐANG BẬN
                                </span>
                            @endif
                        </td>
                        <td class="align-middle text-center">
                            <div class="btn-group">
                                <a href="{{ route('equipment.show', $item) }}" class="btn btn-info text-white" title="Chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('equipment.edit', $item) }}" class="btn btn-warning" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="submit" form="delete-form-{{ $item->id }}" class="btn btn-danger" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $item->id }}" action="{{ route('equipment.destroy', $item) }}" method="POST" class="d-none delete-form">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-box-open fa-3x mb-3 text-muted opacity-25"></i>
                            <p class="text-muted">Không tìm thấy thiết bị nào.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer border-top">
        <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted fw-bold">Hiển thị {{ $equipment->firstItem() ?? 0 }} - {{ $equipment->lastItem() ?? 0 }} / {{ $equipment->total() }} bản ghi</small>
            {{ $equipment->links() }}
        </div>
    </div>
</div>
@endsection
