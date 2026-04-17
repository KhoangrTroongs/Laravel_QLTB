@extends('layouts.public')

@section('title', 'Thông Báo Của Bạn')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 font-weight-bold text-dark">
                    <i class="fas fa-bell mr-2 text-primary"></i>Thông báo của bạn
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
                            <span class="bg-primary shadow-sm px-3">{{ $currentDate }}</span>
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
                        
                        <i class="fas {{ $icon }} bg-{{ $color }} shadow-sm"></i>
                        <div class="timeline-item shadow-sm border-0 {{ $notification->read_at ? 'read-item' : 'unread-item' }}" style="border-radius: 16px !important;">
                            <span class="time text-muted"><i class="fas fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}</span>
                            <h3 class="timeline-header border-0 {{ $notification->read_at ? 'text-muted' : 'font-weight-bold' }}" style="font-size: 1.1rem !important;">
                                {{ $notification->data['title'] }}
                            </h3>
                            <div class="timeline-body py-2 text-muted">
                                {{ $notification->data['message'] }}
                            </div>
                            <div class="timeline-footer pb-4 pt-1">
                                <a href="{{ $notification->data['link'] ?? '#' }}" class="btn btn-sm btn-{{ $color }} rounded-pill px-4 mr-2">
                                    Chi tiết
                                </a>
                                @if(!$notification->read_at)
                                    <form action="{{ route('profile.notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3">OK</button>
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
                <div><i class="fas fa-clock bg-gray"></i></div>
                @endif
            </div>

            <div class="mt-4 d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .timeline { position: relative; margin: 0 0 30px 0; padding: 0; list-style: none; }
    .timeline::before { content: ''; position: absolute; top: 0; bottom: 0; width: 4px; background: #e9ecef; left: 31px; margin: 0; border-radius: 2px; }
    .timeline > div { position: relative; margin-right: 10px; margin-bottom: 25px; }
    .timeline > div > i { width: 32px; height: 32px; font-size: 14px; line-height: 32px; position: absolute; color: #fff; background-color: #adb5bd; border-radius: 50%; text-align: center; left: 18px; top: 0; }
    .timeline-item { margin-left: 60px; position: relative; background-color: #fff; padding: 0; }
    .unread-item { border-left: 5px solid #3b82f6 !important; background-color: #f8fafc !important; }
    .read-item { opacity: 0.8; }
    .time-label { margin-bottom: 25px; }
    .time-label span { font-weight: 600; padding: 6px 15px; border-radius: 20px !important; font-size: 0.85rem; }
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
