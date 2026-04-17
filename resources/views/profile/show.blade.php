@extends('layouts.public')
@section('title', 'Hồ Sơ Cá Nhân')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar Hồ Sơ & Chỉnh sửa -->
        <div class="col-md-4 mb-4">
            <!-- Tóm tắt hồ sơ -->
            <div class="card text-center mb-4" style="border-radius: 24px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.07); overflow: hidden;">
                <div style="height: 80px; background: linear-gradient(135deg, #3b82f6, #2563eb);"></div>
                <div class="card-body pt-0" style="padding: 1.5rem;">
                    <div style="margin-top: -50px; margin-bottom: 1rem;">
                        @if($user->avatar)
                            <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}"
                                 class="rounded-circle border border-4 border-white shadow"
                                 style="width: 100px; height: 100px; object-fit: cover; border-width: 4px !important;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=128&background=3b82f6&color=fff"
                                 class="rounded-circle border border-white shadow"
                                 style="width: 100px; height: 100px; border-width: 4px !important;">
                        @endif
                    </div>
                    <h5 class="font-weight-bold mb-1">{{ $user->name }}</h5>
                    <p class="text-muted small mb-2">{{ $user->email }}</p>
                    <div>
                        @foreach($user->roles as $role)
                            <span class="badge {{ $role->name === 'admin' ? 'badge-danger' : ($role->name === 'editor' ? 'badge-primary' : 'badge-secondary') }}"
                                  style="border-radius: 8px; padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                                {{ $role->display_name }}
                            </span>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-white py-3" style="border-top: 1px solid #f1f5f9;">
                    <div class="row text-center">
                        <div class="col">
                            <div class="font-weight-bold text-dark">{{ $user->employee_id }}</div>
                            <div class="text-muted small">Mã NV</div>
                        </div>
                        <div class="col border-left">
                            <div class="font-weight-bold {{ $user->status ? 'text-success' : 'text-muted' }}">
                                {{ $user->status ? 'Hoạt động' : 'Nghỉ việc' }}
                            </div>
                            <div class="text-muted small">Trạng thái</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Chỉnh Sửa Thông Tin (Đã di chuyển) -->
            <div class="card mb-4" style="border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.07);">
                <div class="card-header bg-white" style="border-radius: 20px 20px 0 0; padding: 1.25rem; border-bottom: 1px solid #f1f5f9;">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-user-edit mr-2 text-primary"></i> Chỉnh Sửa
                    </h6>
                </div>
                <div class="card-body p-3">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="form-group mb-3 text-center">
                            <div class="custom-file-wrapper" style="max-width: 250px; margin: 0 auto;">
                                <input type="file" name="avatar" id="avatarInput" class="custom-file-input-hidden" accept="image/*" onchange="handleFileSelect(this)">
                                <label for="avatarInput" class="custom-file-trigger d-flex align-items-center justify-content-center">
                                    <div class="icon-box mr-2"><i class="fas fa-camera"></i></div>
                                    <span class="file-name text-muted small">Thay ảnh đại diện</span>
                                </label>
                            </div>
                            @error('avatar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group mb-2">
                            <label class="font-weight-bold text-muted extra-small">HỌ VÀ TÊN *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control form-control-sm" style="border-radius: 8px;">
                            @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group mb-2">
                            <label class="font-weight-bold text-muted extra-small">SỐ ĐIỆN THOẠI</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control form-control-sm" style="border-radius: 8px;">
                            @error('phone') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-muted extra-small">ĐỊA CHỈ</label>
                            <textarea name="address" class="form-control form-control-sm" rows="2" style="border-radius: 8px; resize: none;">{{ old('address', $user->address) }}</textarea>
                            @error('address') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-sm py-2" style="border-radius: 8px; font-weight: 700;">
                            <i class="fas fa-save mr-1"></i> Cập Nhật
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card Đổi Mật Khẩu (Đã di chuyển) -->
            <div class="card mb-4" style="border-radius: 20px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.07);">
                <div class="card-header bg-white" style="border-radius: 20px 20px 0 0; padding: 1.25rem; border-bottom: 1px solid #f1f5f9;">
                    <h6 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-key mr-2 text-warning"></i> Đổi Mật Khẩu
                    </h6>
                </div>
                <div class="card-body p-3">
                    <form method="POST" action="{{ route('profile.change-password') }}">
                        @csrf @method('PUT')
                        <div class="form-group mb-2">
                            <label class="font-weight-bold text-muted extra-small">MẬT KHẨU CŨ</label>
                            <div class="input-group-auth">
                                <input type="password" name="current_password" id="p_cur" class="form-control form-control-sm">
                                <button class="btn-toggle-pwd" type="button" onclick="tgl('p_cur', 'i_cur')">
                                    <i class="fas fa-eye" id="i_cur"></i>
                                </button>
                            </div>
                            @error('current_password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-2">
                            <label class="font-weight-bold text-muted extra-small">MẬT KHẨU MỚI</label>
                            <div class="input-group-auth">
                                <input type="password" name="password" id="p_new" class="form-control form-control-sm">
                                <button class="btn-toggle-pwd" type="button" onclick="tgl('p_new', 'i_new')">
                                    <i class="fas fa-eye" id="i_new"></i>
                                </button>
                            </div>
                            @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-muted extra-small">XÁC NHẬN MỚI</label>
                            <div class="input-group-auth">
                                <input type="password" name="password_confirmation" id="p_conf" class="form-control form-control-sm">
                                <button class="btn-toggle-pwd" type="button" onclick="tgl('p_conf', 'i_conf')">
                                    <i class="fas fa-eye" id="i_conf"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning btn-block btn-sm py-2" style="border-radius: 8px; font-weight: 700;">
                            <i class="fas fa-lock mr-1"></i> Đổi Mật Khẩu
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cột Báo Cáo Thiết Bị Mượn (Chính giữa bên phải) -->
        <div class="col-md-8">
            <div class="card h-100" style="border-radius: 24px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.07); overflow: hidden;">
                <div class="card-header bg-white d-flex align-items-center justify-content-between" style="padding: 1.75rem 2rem; border-bottom: 1px solid #f1f5f9;">
                    <h5 class="mb-0 font-weight-bold text-dark">
                        <i class="fas fa-history mr-2 text-primary"></i> Lịch Sử Mượn Thiết Bị
                    </h5>
                    <span class="badge badge-pill badge-light text-muted px-3 py-2" style="font-size: 0.85rem;">
                        Tổng cộng: {{ $user->equipments->count() }}
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="vertical-align: middle;">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 pl-4 py-3 text-muted small font-weight-bold">THIẾT BỊ</th>
                                    <th class="border-0 py-3 text-muted small font-weight-bold">THỜI GIAN</th>
                                    <th class="border-0 py-3 text-muted small font-weight-bold text-center">TRẠNG THÁI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($user->equipments as $equipment)
                                    <tr>
                                        <td class="pl-4 py-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-2 mr-3 text-primary" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                                                    <i class="fas fa-{{ str_contains(strtolower($equipment->name), 'laptop') ? 'laptop' : (str_contains(strtolower($equipment->name), 'chuột') ? 'mouse' : 'tools') }}"></i>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold text-dark mb-0">{{ $equipment->name }}</div>
                                                    <div class="text-muted extra-small">{{ $equipment->model }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4">
                                            <div class="text-dark small"><i class="far fa-calendar-alt mr-1 text-muted"></i> {{ \Carbon\Carbon::parse($equipment->pivot->ngaymuon)->format('d/m/Y') }}</div>
                                            <div class="text-muted extra-small">{{ \Carbon\Carbon::parse($equipment->pivot->ngaymuon)->diffForHumans() }}</div>
                                        </td>
                                        <td class="py-4 text-center">
                                            @if($equipment->pivot->status == 1)
                                                <span class="badge badge-primary px-3 py-2" style="border-radius: 8px; font-weight: 600;">
                                                    <i class="fas fa-clock mr-1"></i> Đang mượn
                                                </span>
                                            @else
                                                <span class="badge badge-success px-3 py-2" style="border-radius: 8px; font-weight: 600;">
                                                    <i class="fas fa-check-circle mr-1"></i> Đã trả
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <div class="mb-3" style="font-size: 3rem; opacity: 0.1; color: #3b82f6;">
                                                <i class="fas fa-box-open"></i>
                                            </div>
                                            <p class="text-muted">Bạn chưa mượn thiết bị nào.</p>
                                            <a href="{{ route('home') }}" class="btn btn-primary btn-sm px-4" style="border-radius: 10px;">
                                                Mượn ngay
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .extra-small { font-size: 0.7rem; letter-spacing: 0.5px; text-transform: uppercase; }
            .form-control-sm { padding: 0.6rem 0.8rem; font-size: 0.85rem; }
            .custom-file-trigger { 
                padding: 0.6rem 1rem !important; 
                border-radius: 10px !important; 
                min-height: auto !important;
            }
            .custom-file-trigger .icon-box { width: 20px !important; height: 20px !important; font-size: 0.75rem !important; }
        </style>
    </div>
</div>
@endsection

@push('scripts')
<script>
function handleFileSelect(input) {
    const fileNameSpan = input.closest('.custom-file-wrapper').querySelector('.file-name');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Update label text
        fileNameSpan.textContent = file.name;
        fileNameSpan.classList.remove('text-muted');
        fileNameSpan.classList.add('text-primary', 'font-weight-bold');
        
        // Preview image
        var reader = new FileReader();
        reader.onload = function(e) {
            // Update sidebar avatar
            const avatarImg = document.querySelector('.card img.rounded-circle');
            if (avatarImg) {
                avatarImg.src = e.target.result;
            }
        }
        reader.readAsDataURL(file);
    } else {
        fileNameSpan.textContent = 'Bấm để chọn ảnh...';
        fileNameSpan.classList.remove('text-primary', 'font-weight-bold');
        fileNameSpan.classList.add('text-muted');
    }
}

function tgl(id, iconId) {
    const p = document.getElementById(id);
    const i = document.getElementById(iconId);
    if (p.type === 'password') {
        p.type = 'text';
        i.className = 'fas fa-eye-slash';
    } else {
        p.type = 'password';
        i.className = 'fas fa-eye';
    }
}
</script>
@endpush
