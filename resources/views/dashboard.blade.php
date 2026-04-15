@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Tổng Quan Hệ Thống')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-white border-top border-info" style="border-top-width: 4px !important;">
            <div class="inner">
                <h3 class="text-info">{{ $userCount }}</h3>
                <p class="text-muted font-weight-bold">Nhân Viên</p>
            </div>
            <div class="icon">
                <i class="fas fa-users text-info opacity-50"></i>
            </div>
            <a href="{{ route('users.index') }}" class="small-box-footer text-info bg-light">
                Quản lý nhân sự <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-white border-top border-success" style="border-top-width: 4px !important;">
            <div class="inner">
                <h3 class="text-success">{{ $equipmentCount }}</h3>
                <p class="text-muted font-weight-bold">Thiết Bị</p>
            </div>
            <div class="icon">
                <i class="fas fa-laptop text-success opacity-50"></i>
            </div>
            <a href="{{ route('equipment.index') }}" class="small-box-footer text-success bg-light">
                Quản lý thiết bị <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-white border-top border-warning" style="border-top-width: 4px !important;">
            <div class="inner">
                <h3 class="text-warning">{{ $borrowingCount }}</h3>
                <p class="text-muted font-weight-bold">Đang Mượn</p>
            </div>
            <div class="icon">
                <i class="fas fa-handshake text-warning opacity-50"></i>
            </div>
            <a href="{{ route('equipment-users.index', ['status' => 1]) }}" class="small-box-footer text-warning bg-light">
                Đang sử dụng <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-white border-top border-danger" style="border-top-width: 4px !important;">
            <div class="inner">
                <h3 class="text-danger">Báo Cáo</h3>
                <p class="text-muted font-weight-bold">Thống Kê</p>
            </div>
            <div class="icon">
                <i class="fas fa-chart-pie text-danger opacity-50"></i>
            </div>
            <a href="{{ route('equipment-users.report') }}" class="small-box-footer text-danger bg-light">
                Xem thống kê <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card card-white shadow-sm">
            <div class="card-body p-5">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h2 class="font-weight-bold text-dark mb-4">Hệ Thống Quản Lý Thiết Bị Nội Bộ</h2>
                        <p class="lead text-muted mb-4">Chào mừng bạn trở lại! Hệ thống giúp bạn theo dõi tài sản công ty một cách minh bạch và hiệu quả.</p>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-soft rounded-circle p-2 mr-3" style="background: rgba(0,123,255,0.1);">
                                        <i class="fas fa-shield-alt text-primary"></i>
                                    </div>
                                    <span class="font-weight-bold">Bảo mật dữ liệu</span>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success-soft rounded-circle p-2 mr-3" style="background: rgba(40,167,69,0.1);">
                                        <i class="fas fa-bolt text-success"></i>
                                    </div>
                                    <span class="font-weight-bold">Phản hồi nhanh</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 text-center d-none d-md-block">
                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/equipment-management-illustration-download-in-svg-png-gif-formats--inventory-asset-maintenance-pack-business-illustrations-4795898.png" 
                             class="img-fluid" style="max-height: 250px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
