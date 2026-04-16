<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\Equipment;
use App\Models\EquipmentUser;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ─── Trang thông báo bị chặn ────────────────────────────────────────────────
Route::get('/banned', function () {
    if (!Auth::check() || (!Auth::user()->trashed() && Auth::user()->available != 0)) {
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
    Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
});

// ─── Dashboard & Quản trị (chỉ admin + editor) ───────────────────────────────
Route::middleware(['auth', 'role:admin,editor'])->group(function () {
    Route::get('/dashboard', function () {
        $userCount = User::count();
        $equipmentCount = Equipment::count();
        $borrowingCount = EquipmentUser::where('status', 1)->count();
        $latestRecords = EquipmentUser::with([
            'user' => fn($q) => $q->withTrashed(),
            'equipment' => fn($q) => $q->withTrashed()
        ])->latest()->take(5)->get();
        $availableEquipmentCount = Equipment::count() - $borrowingCount;

        return view('dashboard', compact(
            'userCount',
            'equipmentCount',
            'borrowingCount',
            'latestRecords',
            'availableEquipmentCount'
        ));
    })->name('dashboard');

    Route::resource('equipment', EquipmentController::class);

    Route::get('equipment-users/report', [EquipmentUserController::class, 'report'])->name('equipment-users.report');
    Route::resource('equipment-users', EquipmentUserController::class);

    // Thùng rác
    Route::get('/trash', [\App\Http\Controllers\TrashController::class, 'index'])->name('trash.index');
    Route::post('/trash/user/{id}/restore', [\App\Http\Controllers\TrashController::class, 'restoreUser'])->name('trash.user.restore');
    Route::post('/trash/equipment/{id}/restore', [\App\Http\Controllers\TrashController::class, 'restoreEquipment'])->name('trash.equipment.restore');
});

// ─── Quản lý nhân viên & vai trò (chỉ admin) ─────────────────────────────────
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles/assign/{user}', [RoleController::class, 'assignRole'])->name('roles.assign');
    Route::delete('/roles/{user}/{role}', [RoleController::class, 'removeRole'])->name('roles.remove');
});
