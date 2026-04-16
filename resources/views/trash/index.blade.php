@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-trash-alt mr-2"></i> Thùng rác</h1>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Nhân viên đã xoá -->
            @if(auth()->user()->isAdmin())
            <div class="col-lg-6">
                <div class="card card-outline card-danger">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Nhân viên đã xoá mềm</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên nhân viên</th>
                                    <th>Ngày xoá</th>
                                    <th class="text-right">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deletedUsers as $user)
                                <tr>
                                    <td><span class="badge badge-secondary">{{ $user->employee_id }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle mr-2" width="30" height="30">
                                            @else
                                                <div class="bg-secondary rounded-circle mr-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                    <small>{{ substr($user->name, 0, 1) }}</small>
                                                </div>
                                            @endif
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td>{{ $user->deleted_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-right">
                                        <form action="{{ route('trash.user.restore', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-undo mr-1"></i> Khôi phục
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Thùng rác nhân viên trống.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Thiết bị đã xoá -->
            <div class="{{ auth()->user()->isAdmin() ? 'col-lg-6' : 'col-lg-12' }}">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">Thiết bị đã xoá mềm</h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Thiết bị</th>
                                    <th>Model</th>
                                    <th>Ngày xoá</th>
                                    <th class="text-right">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deletedEquipment as $eq)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($eq->image)
                                                <img src="{{ asset('storage/' . $eq->image) }}" class="rounded mr-2" width="30" height="30" style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded mr-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                    <i class="fas fa-laptop text-muted" style="font-size: 0.8rem;"></i>
                                                </div>
                                            @endif
                                            {{ $eq->name }}
                                        </div>
                                    </td>
                                    <td><code>{{ $eq->model }}</code></td>
                                    <td>{{ $eq->deleted_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-right">
                                        <form action="{{ route('trash.equipment.restore', $eq->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-undo mr-1"></i> Khôi phục
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Thùng rác thiết bị trống.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
