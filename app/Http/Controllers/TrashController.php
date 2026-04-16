<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrashController extends Controller
{
    /**
     * Display trash contents.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // Chỉ Admin mới được xem nhân viên đã xoá
        $deletedUsers = $user->isAdmin() ? User::onlyTrashed()->get() : collect();
        $deletedEquipment = Equipment::onlyTrashed()->get();

        return view('trash.index', compact('deletedUsers', 'deletedEquipment'));
    }

    /**
     * Restore a user.
     */
    public function restoreUser($id)
    {
        /** @var User $admin */
        $admin = Auth::user();

        if (!$admin->isAdmin()) {
            return back()->with('error', 'Bạn không có quyền khôi phục nhân viên!');
        }

        $user = User::onlyTrashed()->findOrFail($id);
        $user->update(['available' => 1]);
        $user->restore();

        return redirect()->route('trash.index')->with('success', "Đã khôi phục nhân viên: {$user->name}");
    }

    /**
     * Restore equipment.
     */
    public function restoreEquipment($id)
    {
        $equipment = Equipment::onlyTrashed()->findOrFail($id);
        $equipment->update(['available' => 1]);
        $equipment->restore();

        return redirect()->route('trash.index')->with('success', "Đã khôi phục thiết bị: {$equipment->name}");
    }
}
