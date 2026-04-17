@extends('layouts.public')
@section('title', 'Hồ Sơ Cá Nhân')

@section('content')
<div class="container py-5">
    <!-- Hero Section with blurred background effect -->
    <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 24px; background: #fff;">
        <div style="height: 180px; background: url('https://images.unsplash.com/photo-1557683316-973673baf926?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80') center/cover; position: relative;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(30, 41, 59, 0.4);"></div>
        </div>
        <div class="card-body text-center" style="position: relative; margin-top: -70px; padding-bottom: 2.5rem;">
            <div class="mb-3">
                @if($user->avatar)
                    <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}"
                         class="rounded-circle border border-4 border-white shadow-lg mx-auto"
                         style="width: 140px; height: 140px; object-fit: cover; background: #fff;">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=140&background=3b82f6&color=fff"
                         class="rounded-circle border border-4 border-white shadow-lg mx-auto"
                         style="width: 140px; height: 140px; background: #fff;">
                @endif
            </div>
            <h3 class="font-weight-bold mb-1 text-dark">{{ $user->name }}</h3>
            <p class="text-muted mb-3"><i class="fas fa-id-badge mr-1"></i> {{ $user->employee_id }} — {{ $user->email }}</p>
            <div class="d-flex justify-content-center" style="gap: 10px;">
                @foreach($user->roles as $role)
                    <span class="badge {{ $role->name === 'admin' ? 'bg-danger text-white' : 'bg-primary text-white' }} px-3 py-2 rounded-pill shadow-xs" style="font-size: 0.7rem; font-weight: 600; letter-spacing: 0.5px;">
                        {{ $role->display_name }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h6 class="font-weight-bold text-dark"><i class="fas fa-address-book mr-2 text-primary"></i>Liên Hệ & Thống Kê</h6>
                </div>
                <div class="card-body p-4">
                    <div class="p-3 bg-light rounded-lg mb-3">
                        <div class="small text-muted mb-1">Số điện thoại</div>
                        <div class="font-weight-bold">{{ $user->phone ?? 'Chưa cập nhật' }}</div>
                    </div>
                    <div class="p-3 bg-light rounded-lg mb-4">
                        <div class="small text-muted mb-1">Địa chỉ cá nhân</div>
                        <div class="font-weight-600 mb-0" style="line-height: 1.4;">{{ $user->address ?? 'Chưa cập nhật' }}</div>
                    </div>
                    <div class="row no-gutters text-center py-3 border-top">
                        <div class="col-6 border-right">
                            <div class="h5 font-weight-bold text-primary mb-0">{{ $user->equipments->where('pivot.status', 1)->count() }}</div>
                            <div class="text-xs text-muted uppercase font-weight-bold">Đang giữ</div>
                        </div>
                        <div class="col-6">
                            <div class="h5 font-weight-bold text-dark mb-0">{{ $user->equipments->count() }}</div>
                            <div class="text-xs text-muted uppercase font-weight-bold">Tổng mượn</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Reminder -->
            <div class="card border-0 shadow-sm" style="border-radius: 20px; background: #fff;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-shield-alt text-success mr-2"></i>
                        <span class="font-weight-bold small text-dark">Bảo mật tài khoản</span>
                    </div>
                    <p class="text-muted small mb-0 mt-2">
                        Thay đổi mật khẩu định kỳ 3 tháng một lần để bảo vệ dữ liệu cá nhân của bạn.
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content with Nav Tabs Inside Card Header -->
        <div class="col-lg-8">
            <div class="card card-primary card-outline card-outline-tabs border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header p-0 border-bottom-0 bg-white">
                    <ul class="nav nav-tabs custom-adminlte-tabs" id="profileTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold px-4 py-3" id="timeline-tab" data-toggle="pill" href="#timeline" role="tab" style="border-top: none; border-left: none; border-right: none;">
                                <i class="fas fa-stream mr-2"></i>Hành trình mượn trả
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold px-4 py-3" id="settings-tab" data-toggle="pill" href="#settings" role="tab" style="border-top: none; border-left: none; border-right: none;">
                                <i class="fas fa-user-cog mr-2"></i>Cài đặt tài khoản
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body bg-white p-4" style="min-height: 550px;">
                    <div class="tab-content" id="profileTabContent">
                        <!-- Tab 1: Timeline -->
                        <div class="tab-pane fade show active" id="timeline" role="tabpanel">
                            <div class="timeline ml-2">
                                @forelse($user->equipments()->orderBy('equipment_users.id', 'desc')->get() as $equipment)
                                    @php
                                        $status = $equipment->pivot->status;
                                        $iconClass = match($status) {
                                            1 => 'fa-laptop bg-warning elevation-1',
                                            2 => 'fa-times bg-danger',
                                            3 => 'fa-check bg-success elevation-1',
                                            default => 'fa-clock bg-info'
                                        };
                                        $color = match($status) {
                                            1 => '#f59e0b',
                                            2 => '#ef4444',
                                            3 => '#10b981',
                                            default => '#3b82f6'
                                        };
                                    @endphp
                                    <div>
                                        <i class="fas {{ $iconClass }} text-white"></i>
                                        <div class="timeline-item shadow-none border mb-4" style="border-radius: 12px; border-left: 4px solid {{ $color }} !important;">
                                            <span class="time text-muted small"><i class="fas fa-history mr-1"></i>{{ \Carbon\Carbon::parse($equipment->pivot->ngaymuon)->diffForHumans() }}</span>
                                            <h3 class="timeline-header font-weight-bold border-0 pt-3">
                                                {{ $equipment->name }}
                                            </h3>
                                            <div class="timeline-body py-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="text-muted small">Model:</span>
                                                        <span class="font-weight-bold text-dark ml-1">{{ $equipment->model }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="badge px-3 py-1" style="background: {{ $color }}15; color: {{ $color }}; border-radius: 6px; font-size: 0.75rem;">
                                                            {{ match($status) { 1 => 'Đang mượn', 2 => 'Từ chối', 3 => 'Đã trả', default => 'Chờ duyệt' } }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <i class="fas fa-rocket fa-3x text-muted opacity-25 mb-3"></i>
                                        <p class="text-muted font-weight-bold">Hãy mượn thiết bị đầu tiên của bạn!</p>
                                    </div>
                                @endforelse
                                <div><i class="fas fa-flag-checkered bg-gray text-white"></i></div>
                            </div>
                        </div>

                        <!-- Tab 2: Settings -->
                        <div class="tab-pane fade" id="settings" role="tabpanel">
                            <div class="row">
                                <!-- Profile settings form group -->
                                <div class="col-md-12 mb-5">
                                    <div class="p-3 mb-4 bg-light rounded-lg d-flex align-items-center">
                                        <i class="fas fa-user-edit text-primary mr-3 h5 mb-0"></i>
                                        <div class="font-weight-bold text-dark">Chỉnh sửa hồ sơ</div>
                                    </div>
                                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                        @csrf @method('PUT')
                                        <div class="row">
                                            <div class="col-md-12 text-center mb-4">
                                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" onclick="document.getElementById('avatarInput').click()">
                                                    <i class="fas fa-camera mr-2"></i>Thay ảnh đại diện
                                                </button>
                                                <input type="file" name="avatar" id="avatarInput" class="d-none" onchange="handleFileSelect(this)">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="font-weight-bold small text-muted">HỌ VÀ TÊN</label>
                                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control form-control-modern">
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label class="font-weight-bold small text-muted">SỐ ĐIỆN THOẠI</label>
                                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control form-control-modern">
                                            </div>
                                            <div class="col-md-12 form-group mb-4">
                                                <label class="font-weight-bold small text-muted">ĐỊA CHỈ</label>
                                                <textarea name="address" class="form-control form-control-modern" rows="2">{{ old('address', $user->address) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary px-5 shadow-sm font-weight-bold">Lưu Thông Tin</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Security form group -->
                                <div class="col-md-12">
                                    <div class="p-3 mb-4 bg-light rounded-lg d-flex align-items-center">
                                        <i class="fas fa-lock text-warning mr-3 h5 mb-0"></i>
                                        <div class="font-weight-bold text-dark">Đổi mật khẩu bảo mật</div>
                                    </div>
                                    <form method="POST" action="{{ route('profile.change-password') }}">
                                        @csrf @method('PUT')
                                        <div class="row">
                                            <div class="col-md-4 form-group">
                                                <label class="font-weight-bold small text-muted">MẬT KHẨU CŨ</label>
                                                <div class="position-relative">
                                                    <input type="password" name="current_password" id="old_p" class="form-control form-control-modern">
                                                    <i class="fas fa-eye text-muted" style="position: absolute; right: 12px; top: 12px; cursor: pointer;" onclick="tgl('old_p', this)"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label class="font-weight-bold small text-muted">MẬT KHẨU MỚI</label>
                                                <div class="position-relative">
                                                    <input type="password" name="password" id="new_p" class="form-control form-control-modern">
                                                    <i class="fas fa-eye text-muted" style="position: absolute; right: 12px; top: 12px; cursor: pointer;" onclick="tgl('new_p', this)"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-4 form-group mb-4">
                                                <label class="font-weight-bold small text-muted">XÁC NHẬN MỚI</label>
                                                <input type="password" name="password_confirmation" class="form-control form-control-modern">
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-warning px-5 shadow-sm font-weight-bold">Cập Nhật Mật Khẩu</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f4f6f9; }
    .custom-adminlte-tabs .nav-link { border: none !important; color: #6c757d; border-bottom: 3px solid transparent !important; }
    .custom-adminlte-tabs .nav-link.active { background-color: transparent !important; color: #007bff !important; border-bottom: 3px solid #007bff !important; }
    .custom-adminlte-tabs .nav-link:hover { color: #007bff; }
    
    .form-control-modern { border-radius: 8px; border: 1px solid #ddd; padding: 0.6rem 0.85rem; font-size: 0.9rem; }
    .form-control-modern:focus { border-color: #007bff; box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1); }
    
    .timeline::before { left: 24px !important; }
    .timeline > div > i { left: 10px !important; width: 30px !important; height: 30px !important; line-height: 30px !important; }
    .timeline-item { background: #fff !important; margin-left: 55px !important; }
    
    .rounded-lg { border-radius: 12px !important; }
    .font-weight-600 { font-weight: 600; }
    .text-xs { font-size: 0.65rem; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
</style>
@endsection

@push('scripts')
<script>
function tgl(id, el) {
    const p = document.getElementById(id);
    if (p.type === 'password') {
        p.type = 'text';
        el.className = 'fas fa-eye-slash text-muted';
    } else {
        p.type = 'password';
        el.className = 'fas fa-eye text-muted';
    }
}
function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.querySelectorAll('img.rounded-circle').forEach(img => {
                img.src = e.target.result;
            });
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
