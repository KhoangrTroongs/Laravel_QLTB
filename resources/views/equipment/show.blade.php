@extends('layouts.app')

@section('title', 'Chi Tiết Thiết Bị')
@section('page-title', 'Chi Tiết Thiết Bị')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('equipment.index') }}">Thiết Bị</a></li>
    <li class="breadcrumb-item active">{{ $equipment->name }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Profile Image -->
        <div class="card card-primary card-outline shadow-sm bg-white" style="border-radius: 15px;">
            <div class="card-body box-profile">
                <div class="text-center mb-4 pt-3">
                    @if($equipment->image)
                        <img class="img-fluid rounded shadow-sm border" 
                             src="{{ str_starts_with($equipment->image, 'http') ? $equipment->image : asset('storage/' . $equipment->image) }}" 
                             alt="{{ $equipment->name }}" style="max-height: 280px; object-fit: contain; width: 100%;">
                    @else
                        <div class="bg-light py-5 rounded border text-muted opacity-50">
                            <i class="fas fa-laptop fa-5x"></i>
                        </div>
                    @endif
                </div>

                <h3 class="profile-username text-center font-weight-bold text-dark mb-1">{{ $equipment->name }}</h3>
                <p class="text-muted text-center text-uppercase small font-weight-bold mb-4">{{ $equipment->model }}</p>

                <div class="d-flex justify-content-center gap-2 mb-4">
                    @if($equipment->status == 1)
                        <span class="badge badge-success px-3 py-2 shadow-xs mr-2" style="border-radius: 8px;">
                            <i class="fas fa-check-circle mr-1"></i> HOẠT ĐỘNG
                        </span>
                    @else
                        <span class="badge badge-danger px-3 py-2 shadow-xs mr-2" style="border-radius: 8px;">
                            <i class="fas fa-tools mr-1"></i> HỎNG/BẢO TRÌ
                        </span>
                    @endif

                    @php
                        $isBorrowed = $equipment->users->where('pivot.status', \App\Models\EquipmentUser::STATUS_BORROWING)->count() > 0;
                    @endphp
                    @if($equipment->available == 1 && !$isBorrowed)
                        <span class="badge badge-primary px-3 py-2 shadow-xs" style="border-radius: 8px;">
                            <i class="fas fa-unlock mr-1"></i> SẴN SÀNG
                        </span>
                    @else
                        <span class="badge badge-warning px-3 py-2 shadow-xs" style="border-radius: 8px;">
                            <i class="fas fa-lock mr-1"></i> ĐANG BẬN
                        </span>
                    @endif
                </div>

                <hr class="my-4">

                <div class="px-2">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small font-weight-bold">LOẠI THIẾT BỊ:</span>
                        <span class="text-primary font-weight-bold">{{ $equipment->category->name ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small font-weight-bold">PHIÊN BẢN/TAG:</span>
                        <span class="text-dark">{{ $equipment->model }}</span>
                    </div>
                </div>

                <a href="{{ route('equipment.edit', $equipment) }}" class="btn btn-warning btn-block font-weight-bold mt-4 shadow-sm py-2">
                    <i class="fas fa-edit mr-1"></i> CHỈNH SỬA THÔNG TIN
                </a>
            </div>
        </div>

        <!-- Specs Box -->
        <div class="card card-outline card-info shadow-sm mt-4" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom-0">
                <h3 class="card-title font-weight-bold text-info"><i class="fas fa-microchip mr-2"></i>Cấu hình chi tiết</h3>
            </div>
            <div class="card-body pt-0">
                @if($equipment->spec)
                    <div class="bg-light rounded p-3">
                        @foreach($equipment->spec as $key => $value)
                            <div class="mb-2 @if(!$loop->last) border-bottom pb-2 @endif">
                                <span class="text-muted small text-uppercase font-weight-bold d-block">{{ $key }}</span>
                                <span class="text-dark font-weight-600">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted border rounded border-dashed">
                        <i class="fas fa-info-circle mb-2 d-block fa-2x opacity-25"></i>
                        Chưa cập nhật cấu hình.
                    </div>
                @endif
                
                <div class="mt-3">
                     <p class="text-muted small mb-0">{{ $equipment->description ?? 'Không có mô tả thêm.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card card-outline card-primary shadow-sm h-100" style="border-radius: 15px;">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-history mr-2 text-primary"></i>LỊCH SỬ MƯỢN THIẾT BỊ
                </h3>
            </div>
            <div class="card-body bg-light rounded-bottom" style="max-height: 800px; overflow-y: auto;">
                @if($equipment->users->count() > 0)
                <div class="timeline timeline-inverse">
                    @php $lastDate = ''; @endphp
                    @foreach($equipment->users as $user)
                        @php 
                            $date = \Carbon\Carbon::parse($user->pivot->ngaymuon)->format('d/m/Y');
                            $status = $user->pivot->status;
                            
                            $iconClass = 'fas fa-envelope';
                            $bgClass = 'bg-info';
                            $statusLabel = 'Yêu cầu mới';
                            
                            if($status == 1) { // Borrowing
                                $iconClass = 'fas fa-hand-holding-heart';
                                $bgClass = 'bg-warning text-white';
                                $statusLabel = 'Đang mượn';
                            } elseif($status == 2) { // Rejected
                                $iconClass = 'fas fa-times-circle';
                                $bgClass = 'bg-danger';
                                $statusLabel = 'Đã từ chối';
                            } elseif($status == 3) { // Returned
                                $iconClass = 'fas fa-check-circle';
                                $bgClass = 'bg-success';
                                $statusLabel = 'Đã trả';
                            }
                        @endphp

                        @if($date !== $lastDate)
                            <div class="time-label">
                                <span class="bg-primary px-3 shadow-xs" style="border-radius: 20px;">
                                    {{ $date }}
                                </span>
                            </div>
                            @php $lastDate = $date; @endphp
                        @endif

                        <div>
                            <i class="{{ $iconClass }} {{ $bgClass }} shadow-sm"></i>
                            <div class="timeline-item shadow-xs mb-4 border-0" style="border-radius: 12px;">
                                <span class="time text-muted"><i class="far fa-clock mr-1"></i>{{ \Carbon\Carbon::parse($user->pivot->created_at)->diffForHumans() }}</span>
                                <h3 class="timeline-header border-bottom-0 py-3">
                                    <div class="d-flex align-items-center">
                                        @if($user->avatar)
                                            <img src="{{ str_starts_with($user->avatar, 'http') ? $user->avatar : asset('storage/' . $user->avatar) }}" 
                                                 class="rounded-circle mr-2 border shadow-xs" style="width: 35px; height: 35px; object-fit: cover;">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=3b82f6&color=fff" 
                                                 class="rounded-circle mr-2 border shadow-xs" style="width: 35px; height: 35px;">
                                        @endif
                                        <div class="ml-2">
                                            <a href="#" class="font-weight-bold text-dark">{{ $user->name }}</a>
                                            <span class="mx-1 text-muted small">đã</span>
                                            <span class="badge {{ $bgClass }} px-2 py-1 small shadow-xs" style="border-radius: 6px;">{{ $statusLabel }}</span>
                                        </div>
                                    </div>
                                </h3>

                                <div class="timeline-body py-2 px-3 text-muted small bg-white mx-3 mb-2 rounded border shadow-xs">
                                    <p class="mb-1"><strong>Mã nhân viên:</strong> {{ $user->employee_id }}</p>
                                    @if($user->pivot->ngaytra)
                                        <p class="mb-1 text-success font-weight-bold"><i class="fas fa-undo mr-1"></i>Đã hoàn trả ngày: {{ \Carbon\Carbon::parse($user->pivot->ngaytra)->format('d/m/Y H:i') }}</p>
                                    @endif
                                    @if($user->pivot->description)
                                        <p class="mb-0 mt-2 p-2 bg-light rounded text-italic border-left border-info" style="border-left-width: 4px !important;">
                                            <i class="fas fa-comment-dots mr-2 opacity-50"></i>"{{ $user->pivot->description }}"
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div>
                        <i class="fas fa-clock bg-gray shadow-sm"></i>
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <img src="https://cdn-icons-png.flaticon.com/512/1157/1157044.png" width="100" class="opacity-25 mb-3">
                    <h5 class="text-muted">Chưa có lịch sử mượn</h5>
                    <p class="text-muted small">Dữ liệu về việc cấp phát thiết bị này sẽ được hiển thị tại đây.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
