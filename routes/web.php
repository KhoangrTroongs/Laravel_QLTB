<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\UserController;
use App\Models\Equipment;
use App\Models\EquipmentUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ─── Trang thông báo bị chặn ────────────────────────────────────────────────
Route::get('/banned', function () {
    if (! Auth::check() || (! Auth::user()->trashed() && Auth::user()->available != 0)) {
        return redirect()->route('home');
    }

    return view('banned');
})->name('banned');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/thiet-bi/{equipment}', [HomeController::class, 'show'])->name('home.show');

// ─── Tạo phiếu mượn (yêu cầu đăng nhập) ─────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/borrow/{equipment}', [HomeController::class, 'createBorrow'])->name('home.borrow.create');
    Route::post('/borrow/{equipment}', [HomeController::class, 'storeBorrow'])->name('home.borrow.store');
});

// ─── Auth ─────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Hồ sơ cá nhân (yêu cầu đăng nhập) ──────────────────────────────────────
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'show'])->name('show');
    Route::put('/', [ProfileController::class, 'update'])->name('update');
    Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');

    // Thông báo
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
});

// ─── Dashboard & Quản trị (chỉ admin + editor) ───────────────────────────────
Route::middleware(['auth', 'role:admin,editor'])->group(function () {
    Route::get('/dashboard', function () {
        $userCount = User::count();
        $equipmentCount = Equipment::count();
        $borrowingCount = EquipmentUser::where('status', EquipmentUser::STATUS_BORROWING)->count();
        $overdueCount = EquipmentUser::where('status', EquipmentUser::STATUS_BORROWING)
            ->where('hantra', '<', now())->count();
        $latestRecords = EquipmentUser::with([
            'user' => fn ($q) => $q->withTrashed(),
            'equipment' => fn ($q) => $q->withTrashed(),
        ])->latest()->take(5)->get();
        $pendingCount = EquipmentUser::where('status', EquipmentUser::STATUS_PENDING)->count();
        $availableEquipmentCount = Equipment::where('status', 1)->where('available', 1)->count() - $borrowingCount;

        // Dữ liệu cho biểu đồ phân bổ loại thiết bị
        $categoryDistribution = \App\Models\Category::withCount('equipment')->get();
        
        $pendingItems = EquipmentUser::with(['user', 'equipment'])
            ->where('status', EquipmentUser::STATUS_PENDING)
            ->latest()
            ->take(5)
            ->get();

        // Thống kê xu hướng mượn thiết bị (7 ngày gần nhất)
        $borrowingTrends = EquipmentUser::selectRaw('DATE(ngaymuon) as date, count(*) as total')
            ->where('ngaymuon', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top 5 người mượn nhiều nhất
        $topBorrowers = User::withCount('equipments')
            ->orderBy('equipments_count', 'desc')
            ->take(5)
            ->get();

        // Top 5 thiết bị được mượn nhiều nhất
        $popularEquipment = Equipment::withCount('users')
            ->orderBy('users_count', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'userCount',
            'equipmentCount',
            'borrowingCount',
            'overdueCount',
            'latestRecords',
            'availableEquipmentCount',
            'pendingCount',
            'categoryDistribution',
            'pendingItems',
            'borrowingTrends',
            'topBorrowers',
            'popularEquipment'
        ));
    })->name('dashboard');

    Route::resource('equipment', EquipmentController::class);
    Route::resource('categories', CategoryController::class);

    Route::get('equipment-users/report', [EquipmentUserController::class, 'report'])->name('equipment-users.report');
    Route::get('equipment-users/queue', [EquipmentUserController::class, 'queue'])->name('equipment-users.queue');
    Route::patch('equipment-users/{equipmentUser}/approve', [EquipmentUserController::class, 'approve'])->name('equipment-users.approve');
    Route::patch('equipment-users/{equipmentUser}/reject', [EquipmentUserController::class, 'reject'])->name('equipment-users.reject');
    Route::patch('equipment-users/{equipmentUser}/return', [EquipmentUserController::class, 'return'])->name('equipment-users.return');
    Route::resource('equipment-users', EquipmentUserController::class);

    // Thùng rác
    Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
    Route::post('/trash/user/{id}/restore', [TrashController::class, 'restoreUser'])->name('trash.user.restore');
    Route::post('/trash/equipment/{id}/restore', [TrashController::class, 'restoreEquipment'])->name('trash.equipment.restore');
});

// ─── Quản lý nhân viên & vai trò (chỉ admin) ─────────────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles/assign/{user}', [RoleController::class, 'assignRole'])->name('roles.assign');
    Route::delete('/roles/{user}/{role}', [RoleController::class, 'removeRole'])->name('roles.remove');
});
