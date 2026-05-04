@extends('layouts.public')

@section('title', 'Thông Báo Của Bạn')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-bold text-dark">
                    <i class="fas fa-bell me-2 text-primary"></i>Thông báo của bạn
                </h4>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('profile.notifications.markAllAsRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill shadow-sm px-4">
                            Đã đọc tất cả
                        </button>
                    </form>
                @endif
            </div>

            <div class="timeline" id="notifications-timeline">
                @php $lastDate = null; @endphp
                
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

                    <div id="notif-{{ $notification->id }}">
                        @php
                            $type = $notification->data['type'] ?? 'info';
                            $icon = 'fa-bell';
                            $color = 'primary';
                            
                            if ($type == 'request_response') {
                                $icon = 'fa-reply';
                                $color = 'success';
                            }
                        @endphp
                        
                        <i class="timeline-icon fas {{ $icon }} text-bg-{{ $color }}"></i>
                        <div class="timeline-item {{ $notification->read_at ? 'read-item' : 'unread-item' }}">
                            <span class="time"><i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}</span>
                            <h3 class="timeline-header {{ $notification->read_at ? 'text-muted' : 'fw-bold' }}">
                                {{ $notification->data['title'] }}
                            </h3>
                            <div class="timeline-body">
                                {{ $notification->data['message'] }}
                            </div>
                            <div class="timeline-footer">
                                <a href="{{ $notification->data['link'] ?? '#' }}" class="btn btn-sm btn-{{ $color }}">
                                    Chi tiết
                                </a>
                                @if(!$notification->read_at)
                                    <form action="{{ route('profile.notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary">Đã đọc</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 bg-white shadow-sm" style="border-radius: 24px;">
                        <i class="fas fa-comment-slash fa-4x text-muted opacity-25 mb-3"></i>
                        <p class="text-muted">Bạn không có thông báo nào.</p>
                        <a href="{{ route('profile.show') }}" class="btn btn-primary rounded-pill px-4">Xem hồ sơ</a>
                    </div>
                @endforelse
                
                @if($notifications->count() > 0)
                <div><i class="timeline-icon fas fa-clock text-bg-secondary"></i></div>
                @endif
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .unread-item { border-start: 5px solid #3b82f6 !important; background-color: #f8fafc !important; }
    .read-item { opacity: 0.8; }
</style>
@endsection

@push('scripts')
<script>
    if (typeof window.Echo !== 'undefined') {
        window.Echo.private('App.Models.User.{{ auth()->id() }}')
            .notification((notification) => {
                location.reload(); // Simple reload for consistent timeline state
            });
    }
</script>
@endpush
