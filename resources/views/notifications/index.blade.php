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
            <h5 class="mb-0 text-muted font-weight-bold">
                <i class="fas fa-history mr-2"></i>Dòng thời gian thông báo
            </h5>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('profile.notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill shadow-sm px-3">
                        <i class="fas fa-check-double mr-1"></i> Đọc tất cả ({{ auth()->user()->unreadNotifications->count() }})
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
                        <span class="bg-primary shadow-sm px-3">{{ $currentDate }}</span>
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
                    
                    <i class="fas {{ $icon }} bg-{{ $color }} shadow-sm"></i>
                    
                    <div class="timeline-item shadow-sm {{ $notification->read_at ? 'read-item' : 'unread-item' }}">
                        <span class="time text-muted">
                            <i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                        </span>
                        
                        <h3 class="timeline-header border-0 {{ $notification->read_at ? 'text-muted' : 'font-weight-bold' }}">
                            {{ $notification->data['title'] }}
                        </h3>

                        <div class="timeline-body py-2">
                            {{ $notification->data['message'] }}
                        </div>
                        
                        <div class="timeline-footer pb-3 pt-1">
                            <a href="{{ $notification->data['link'] ?? '#' }}" class="btn btn-sm btn-{{ $color }} rounded-pill px-3 shadow-sm mr-2">
                                <i class="fas fa-eye mr-1"></i> Xem chi tiết
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
            
            <div>
                <i class="fas fa-clock bg-gray"></i>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $notifications->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<style>
    .timeline { margin-bottom: 50px; }
    .timeline::before { border-radius: 0.25rem; background-color: #dee2e6; bottom: 0; content: ''; left: 31px; margin: 0; position: absolute; top: 0; width: 4px; }
    .timeline-item { border-radius: 12px !important; margin-bottom: 25px !important; margin-left: 60px !important; border: 1px solid rgba(0,0,0,.08) !important; }
    .unread-item { border-left: 4px solid #3b82f6 !important; background-color: #f8fafc !important; }
    .read-item { opacity: 0.8; }
    .timeline > div > i { left: 18px !important; width: 30px !important; height: 30px !important; font-size: 0.95rem !important; line-height: 30px !important; }
    .timeline > .time-label > span { border-radius: 20px !important; padding: 5px 20px !important; font-size: 0.85rem !important; letter-spacing: 0.5px; }
    .timeline-header { font-size: 1rem !important; padding: 1.25rem 1rem 0.5rem !important; }
    .timeline-body { padding: 0.5rem 1rem !important; font-size: 0.95rem; color: #4b5563; }
    .timeline-footer { padding: 0.5rem 1rem !important; }
</style>
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
                        <i class="fas ${icon} bg-${color} shadow-sm"></i>
                        <div class="timeline-item shadow-sm unread-item" style="background-color: #f0fdf4 !important;">
                            <span class="time text-primary font-weight-bold">
                                <i class="fas fa-clock mr-1"></i>Vừa xong
                            </span>
                            <h3 class="timeline-header border-0 font-weight-bold">${notification.title}</h3>
                            <div class="timeline-body py-2">${notification.message}</div>
                            <div class="timeline-footer pb-3 pt-1">
                                <a href="${notification.link}" class="btn btn-sm btn-${color} rounded-pill px-3 shadow-sm mr-2">
                                    <i class="fas fa-eye mr-1"></i> Xem chi tiết
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
                    const todayLabel = `<div class="time-label"><span class="bg-primary shadow-sm px-3">{{ date("d/m/Y") }}</span></div>`;
                    timeline.prepend($(todayLabel + newHtml));
                }
            });
    }
</script>
@endpush
