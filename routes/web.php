<?php

use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentUserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $userCount = \App\Models\User::count();
    $equipmentCount = \App\Models\Equipment::count();
    $borrowingCount = \App\Models\EquipmentUser::where('status', 1)->count();
    return view('dashboard', compact('userCount', 'equipmentCount', 'borrowingCount'));
})->name('dashboard');

Route::resource('equipment', EquipmentController::class);
Route::resource('users', UserController::class);

Route::get('equipment-users/report', [EquipmentUserController::class, 'report'])->name('equipment-users.report');
Route::resource('equipment-users', EquipmentUserController::class);

