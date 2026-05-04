@extends('layouts.app')

@section('title', 'Quản Lý Loại Thiết Bị')
@section('page-title', 'Quản Lý Loại Thiết Bị')

@section('breadcrumb')
    <li class="breadcrumb-item active">Loại Thiết Bị</li>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title fw-bold"><i class="fas fa-tags me-2 text-primary"></i>Danh Sách Loại Thiết Bị</h3>
                <div class="card-tools">
                    <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm shadow-sm px-3">
                        <i class="fas fa-plus me-1"></i> Thêm Loại Mới
                    </a>
                </div>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="80" class="text-center ps-4 border-top-0">#</th>
                                <th class="border-top-0">TÊN LOẠI</th>
                                <th class="border-top-0">MÔ TẢ</th>
                                <th class="text-center border-top-0">SỐ THIẾT BỊ</th>
                                <th width="150" class="text-center pe-4 border-top-0">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td class="text-center ps-4 align-middle">{{ $category->id }}</td>
                                <td class="align-middle">
                                    <span class="d-block fw-bold">{{ $category->name }}</span>
                                </td>
                                <td class="align-middle text-muted small">
                                    {{ $category->description ?? 'Chưa có mô tả' }}
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge text-bg-info px-3 py-2 shadow-xs" style="border-radius: 8px;">
                                        {{ $category->equipment_count }}
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="submit" form="delete-form-{{ $category->id }}" class="btn btn-danger" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category) }}" method="POST" class="d-none delete-form">
                                        @csrf @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="opacity-25 mb-3"><i class="fas fa-tags fa-4x text-muted"></i></div>
                                    <p class="text-muted">Chưa có loại thiết bị nào tồn tại trong hệ thống.</p>
                                    <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">Bắt đầu thêm ngay</a>
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
@endsection
