<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\EquipmentController;
use App\Http\Controllers\Api\EquipmentUserController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TrashController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/equipment/available', [EquipmentController::class, 'available']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'check.banned'])->group(function () {

    // Broadcast Auth
    Broadcast::routes(['middleware' => ['auth:sanctum']]);

    // Auth & Profile
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/count', [NotificationController::class, 'count']);
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markAsRead']);

    // Equipment (Public view/borrow)
    Route::get('/equipment', [EquipmentController::class, 'index']);
    Route::get('/equipment/{equipment}', [EquipmentController::class, 'show']);
    Route::post('/equipment/{equipment}/borrow', [EquipmentUserController::class, 'storeBorrow']);
    Route::get('/my-borrows', [EquipmentUserController::class, 'myBorrows']);

    // Categories (Read-only for users)
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Admin & Editor Routes
    |--------------------------------------------------------------------------
    */
    // ─── Admin + Editor ───────────────────────────────────────────────────────
    Route::middleware(['role:admin,editor'])->prefix('admin')->group(function () {
        Route::get('dashboard/stats', [DashboardController::class, 'stats']);
        Route::get('reports', [\App\Http\Controllers\Api\ReportController::class, 'index']);

        Route::apiResource('equipment', EquipmentController::class);
        Route::apiResource('categories', CategoryController::class);

        Route::prefix('borrows')->group(function () {
            Route::get('/', [EquipmentUserController::class, 'index']);
            Route::get('queue', [EquipmentUserController::class, 'queue']);
            Route::post('{id}/approve', [EquipmentUserController::class, 'approve']);
            Route::post('{id}/reject', [EquipmentUserController::class, 'reject']);
            Route::post('{id}/return', [EquipmentUserController::class, 'return']);
        });

        Route::get('trash', [TrashController::class, 'index']);
        Route::post('trash/{type}/{id}/restore', [TrashController::class, 'restore']);
        Route::delete('trash/{type}/{id}/force', [TrashController::class, 'forceDelete']);
    });

    // ─── Admin Only ───────────────────────────────────────────────────────────
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::get('roles', [RoleController::class, 'index']);
    });
});
