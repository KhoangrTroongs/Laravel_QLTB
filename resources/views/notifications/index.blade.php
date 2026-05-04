@extends('layouts.app')

@section('title', 'Thông Báo Hệ Thống')
@section('page-title', 'Thông Báo')

@section('breadcrumb')
    <li class="breadcrumb-item active">Thông Báo</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <!-- Action Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 text-muted fw-bold">
                <i class="fas fa-history me-2"></i>Dòng thời gian thông báo
            </h5>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('profile.notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill shadow-sm px-3">
                        <i class="fas fa-check-double me-1"></i> Đọc tất cả ({{ auth()->user()->unreadNotifications->count() }})
                    </button>
                </form>
            @endif
        </div>

        <!-- The Timeline -->
        <div class="timeline" id="notifications-timeline">
            @php 
                $lastDate = null; 
            @endphp
            
            @forelse($notifications as $notification)
                @php 
                    $currentDate = $notification->created_at->format('d/m/Y');
                    $isNewDay = $lastDate !== $currentDate;
                    $lastDate = $currentDate;
                @endphp

                @if($isNewDay)
                    <div class="time-label">
                        <span class="text-bg-primary">{{ $currentDate }}</span>
                    </div>
                @endif

                <div class="notification-item-wrapper" id="notif-{{ $notification->id }}">
                    @php
                        $type = $notification->data['type'] ?? 'info';
                        $icon = 'fa-bell';
                        $color = 'info';
                        
                        if ($type == 'new_request') {
                            $icon = 'fa-paper-plane';
                            $color = 'primary';
                        } elseif ($type == 'overdue_reminder') {
                            $icon = 'fa-exclamation-triangle';
                            $color = 'danger';
                        } elseif ($type == 'request_response') {
                            $icon = 'fa-reply';
                            $color = 'success';
                        }
                    @endphp
                    
                    <i class="timeline-icon fas {{ $icon }} text-bg-{{ $color }}"></i>
                    
                    <div class="timeline-item shadow-sm {{ $notification->read_at ? '' : 'border-start border-primary border-4' }}">
                        <span class="time text-muted">
                            <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                        </span>
                        
                        <h3 class="timeline-header border-0 {{ $notification->read_at ? 'text-secondary' : 'fw-bold' }}">
                            {{ $notification->data['title'] }}
                        </h3>

                        <div class="timeline-body py-2">
                            {{ $notification->data['message'] }}
                        </div>
                        
                        <div class="timeline-footer pb-3 pt-1">
                            <a href="{{ $notification->data['link'] ?? '#' }}" class="btn btn-sm btn-{{ $color }} rounded-pill px-3 shadow-sm me-2">
                                <i class="fas fa-eye me-1"></i> Xem chi tiết
                            </a>
                            
                            @if(!$notification->read_at)
                                <form action="{{ route('profile.notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3">
                                        Đánh dấu đã đọc
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="mb-3 opacity-25">
                        <i class="fas fa-bell-slash fa-4x text-muted"></i>
                    </div>
                    <h5 class="text-muted">Bạn chưa có thông báo nào.</h5>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3 rounded-pill px-4">
                        Quay lại Dashboard
                    </a>
                </div>
            @endforelse
            
            @if($notifications->count() > 0)
            <div>
                <i class="timeline-icon fas fa-clock text-bg-secondary"></i>
            </div>
            @endif
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $notifications->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    if (typeof window.Echo !== 'undefined') {
        window.Echo.private('App.Models.User.{{ auth()->id() }}')
            .notification((notification) => {
                const timeline = $('#notifications-timeline');
                
                // Get config for notification type
                const type = notification.type_name || 'info';
                let icon = 'fa-bell';
                let color = 'info';
                
                if (notification.type.includes('NewBorrowRequest')) {
                    icon = 'fa-paper-plane'; color = 'primary';
                } else if (notification.type.includes('BorrowRequestResponse')) {
                    icon = 'fa-reply'; color = 'success';
                }
                
                const newHtml = `
                    <div class="animate__animated animate__fadeInDown">
                        <i class="timeline-icon fas ${icon} text-bg-${color}"></i>
                        <div class="timeline-item border-start border-primary border-4 bg-light">
                            <span class="time text-primary fw-bold">
                                <i class="fas fa-clock me-1"></i>Vừa xong
                            </span>
                            <h3 class="timeline-header border-0 fw-bold">${notification.title}</h3>
                            <div class="timeline-body py-2">${notification.message}</div>
                            <div class="timeline-footer pb-3 pt-1">
                                <a href="${notification.link}" class="btn btn-sm btn-${color} rounded-pill px-3 shadow-sm me-2">
                                    <i class="fas fa-eye me-1"></i> Xem chi tiết
                                </a>
                                <button class="btn btn-sm btn-light border rounded-pill px-3" onclick="location.reload()">Đã rõ</button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Prepend after the first time-label if today exists, or insert at top
                const firstLabel = timeline.find('.time-label').first();
                if (firstLabel.length > 0 && firstLabel.text().includes('{{ date("d/m/Y") }}')) {
                    firstLabel.after(newHtml);
                } else {
                    const todayLabel = `<div class="time-label"><span class="text-bg-primary">{{ date("d/m/Y") }}</span></div>`;
                    timeline.prepend($(todayLabel + newHtml));
                }
            });
    }
</script>
@endpush
