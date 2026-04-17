@extends('layouts.app')

@section('title', 'Quản Lý Loại Thiết Bị')
@section('page-title', 'Quản Lý Loại Thiết Bị')

@section('breadcrumb')
    <li class="breadcrumb-item active">Loại Thiết Bị</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom-0 pt-3">
                <h3 class="card-title font-weight-bold"><i class="fas fa-tags mr-2 text-primary"></i>Danh Sách Loại Thiết Bị</h3>
                <div class="card-tools">
                    <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm shadow-sm px-3">
                        <i class="fas fa-plus mr-1"></i> Thêm Loại Mới
                    </a>
                </div>
            </div>
            <div class="card-body p-0 mt-2">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="80" class="text-center pl-4 border-top-0">#</th>
                                <th class="border-top-0">TÊN LOẠI</th>
                                <th class="border-top-0">MÔ TẢ</th>
                                <th class="text-center border-top-0">SỐ THIẾT BỊ</th>
                                <th width="150" class="text-center pr-4 border-top-0">THAO TÁC</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td class="text-center text-muted pl-4 align-middle">{{ $category->id }}</td>
                                <td class="align-middle">
                                    <span class="d-block font-weight-bold text-dark">{{ $category->name }}</span>
                                </td>
                                <td class="align-middle text-muted small">
                                    {{ $category->description ?? 'Chưa có mô tả' }}
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge badge-info px-3 py-2 shadow-xs" style="border-radius: 8px;">
                                        {{ $category->equipment_count }}
                                    </span>
                                </td>
                                <td class="text-center pr-4 align-middle">
                                    <div class="btn-group">
                                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning btn-sm shadow-sm mr-2" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="d-inline delete-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm shadow-sm" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
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
