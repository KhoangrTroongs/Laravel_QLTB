@extends('layouts.public')
@section('title', 'Hồ Sơ Cá Nhân')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-3">
        <!-- Left Sidebar: Profile Box & About Me -->
        <div class="col-md-4 col-lg-3">
            <!-- Profile Image -->
            <div class="card card-primary card-outline mb-4">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if($user->avatar)
                            <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}"
                                 class="profile-user-img img-fluid rounded-circle"
                                 alt="User profile picture"
                                 style="border: 3px solid #adb5bd; padding: 3px; width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D8ABC&color=fff"
                                 class="profile-user-img img-fluid rounded-circle"
                                 alt="User profile picture"
                                 style="border: 3px solid #adb5bd; padding: 3px; width: 100px; height: 100px;">
                        @endif
                    </div>

                    <h3 class="profile-username text-center mt-3">{{ $user->name }}</h3>
                    <p class="text-muted text-center mb-1">{{ $user->employee_id }}</p>
                    <p class="text-muted text-center">
                        @foreach($user->roles as $role)
                            <span class="badge {{ $role->name === 'admin' ? 'text-bg-danger' : 'text-bg-primary' }}">{{ $role->display_name }}</span>
                        @endforeach
                    </p>

                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <b>Đang mượn</b> 
                            <a class="text-decoration-none float-end">{{ $user->equipments->where('pivot.status', 1)->count() }}</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <b>Tổng số mượn</b> 
                            <a class="text-decoration-none float-end">{{ $user->equipments->count() }}</a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <b>Điện thoại</b> 
                            <span class="float-end text-muted">{{ $user->phone ?? 'Chưa có' }}</span>
                        </li>
                    </ul>

                    <button type="button" class="btn btn-primary w-100" onclick="document.getElementById('avatarInput').click()">
                        <b>Thay Ảnh Đại Diện</b>
                    </button>
                </div>
            </div>

            <!-- About Me Box -->
            <div class="card card-primary mb-4">
                <div class="card-header">
                    <h3 class="card-title">Về Tôi</h3>
                </div>
                <div class="card-body">
                    <strong><i class="fas fa-envelope me-1"></i> Email</strong>
                    <p class="text-muted">{{ $user->email }}</p>
                    <hr>
                    <strong><i class="fas fa-map-marker-alt me-1"></i> Địa Chỉ</strong>
                    <p class="text-muted">{{ $user->address ?? 'Chưa cập nhật' }}</p>
                    <hr>
                    <strong><i class="fas fa-shield-alt me-1"></i> Bảo Mật</strong>
                    <p class="text-muted mb-0">Nên đổi mật khẩu định kỳ 3 tháng.</p>
                </div>
            </div>
        </div>

        <!-- Right Sidebar: Tabs for Timeline & Settings -->
        <div class="col-md-8 col-lg-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#timeline" data-bs-toggle="tab">Hoạt động</a></li>
                        <li class="nav-item"><a class="nav-link" href="#settings" data-bs-toggle="tab">Cài đặt</a></li>
                        <li class="nav-item"><a class="nav-link" href="#security" data-bs-toggle="tab">Mật khẩu</a></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Timeline Tab -->
                        <div class="active tab-pane" id="timeline">
                            <div class="timeline">
                                @php $lastDate = null; @endphp
                                @forelse($user->equipments()->orderBy('equipment_users.id', 'desc')->get() as $equipment)
                                    @php
                                        $currentDate = \Carbon\Carbon::parse($equipment->pivot->ngaymuon)->format('d/m/Y');
                                        $status = $equipment->pivot->status;
                                        $iconClass = match($status) {
                                            1 => 'fa-laptop text-bg-warning',
                                            2 => 'fa-times text-bg-danger',
                                            3 => 'fa-check text-bg-success',
                                            default => 'fa-clock text-bg-info'
                                        };
                                        $statusText = match($status) { 
                                            1 => 'Đang mượn', 
                                            2 => 'Từ chối', 
                                            3 => 'Đã trả', 
                                            default => 'Chờ duyệt' 
                                        };
                                        $statusColor = match($status) {
                                            1 => 'text-warning',
                                            2 => 'text-danger',
                                            3 => 'text-success',
                                            default => 'text-info'
                                        };
                                    @endphp
                                    
                                    @if($lastDate !== $currentDate)
                                        <div class="time-label">
                                            <span class="text-bg-primary">{{ $currentDate }}</span>
                                        </div>
                                        @php $lastDate = $currentDate; @endphp
                                    @endif

                                    <div>
                                        <i class="timeline-icon fas {{ $iconClass }}"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($equipment->pivot->ngaymuon)->format('H:i') }} ({{ \Carbon\Carbon::parse($equipment->pivot->ngaymuon)->diffForHumans() }})</span>
                                            <h3 class="timeline-header"><a href="{{ route('equipment.show', $equipment->id) }}">{{ $equipment->name }}</a></h3>
                                            <div class="timeline-body">
                                                Model: {{ $equipment->model }}
                                                <br>
                                                Trạng thái: <span class="fw-bold {{ $statusColor }}">{{ $statusText }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Chưa có lịch sử mượn thiết bị.</p>
                                    </div>
                                @endforelse
                                @if($user->equipments->count() > 0)
                                    <div>
                                        <i class="timeline-icon fas fa-clock text-bg-secondary"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Settings Tab -->
                        <div class="tab-pane" id="settings">
                            <form class="form-horizontal" id="profileForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <input type="file" id="avatarInput" class="d-none" name="avatar" onchange="handleFileSelect(this)">
                                
                                <div class="row mb-3">
                                    <label for="inputName" class="col-sm-2 col-form-label">Họ và tên</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputName" name="name" value="{{ old('name', $user->name) }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="inputEmail" value="{{ $user->email }}" disabled>
                                        <small class="text-muted">Email không thể thay đổi.</small>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPhone" class="col-sm-2 col-form-label">Điện thoại</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="inputPhone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputAddress" class="col-sm-2 col-form-label">Địa chỉ</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="inputAddress" name="address">{{ old('address', $user->address) }}</textarea>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="offset-sm-2 col-sm-10">
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane" id="security">
                            <form class="form-horizontal" method="POST" action="{{ route('profile.change-password') }}">
                                @csrf @method('PUT')
                                <div class="row mb-3">
                                    <label for="old_p" class="col-sm-3 col-form-label">Mật khẩu hiện tại</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="old_p" name="current_password" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="tgl('old_p', this)"><i class="fas fa-eye"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="new_p" class="col-sm-3 col-form-label">Mật khẩu mới</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="new_p" name="password" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="tgl('new_p', this)"><i class="fas fa-eye"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="confirm_p" class="col-sm-3 col-form-label">Xác nhận mật khẩu</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_p" name="password_confirmation" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="tgl('confirm_p', this)"><i class="fas fa-eye"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="offset-sm-3 col-sm-9">
                                        <button type="submit" class="btn btn-warning">Cập nhật mật khẩu</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function tgl(id, btn) {
    const p = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (p.type === 'password') {
        p.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        p.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.querySelectorAll('img.profile-user-img').forEach(img => {
                img.src = e.target.result;
            });
            // Automatically submit the form to update avatar
            document.getElementById('profileForm').submit();
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
