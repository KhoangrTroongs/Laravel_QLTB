<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\EquipmentUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Trang chủ: danh sách thiết bị còn có thể cho mượn.
     * Ai cũng có thể xem, nhưng chỉ user đã đăng nhập mới tạo được phiếu mượn.
     */
    public function index(Request $request): View
    {
        $query = Equipment::query()->where('status', 1)->where('available', 1);

        // Loại bỏ thiết bị đang được mượn (có phiếu mượn status=1)
        $query->whereDoesntHave('users', function ($q) {
            $q->where('equipment_users.status', 1);
        });

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('model', 'like', '%'.$request->search.'%');
            });
        }

        $availableEquipment = $query->latest()->paginate(12)->withQueryString();

        return view('home.index', compact('availableEquipment'));
    }

    /**
     * Trang chi tiết thiết bị.
     */
    public function show(Equipment $equipment): View
    {
        if ($equipment->available == 0) {
            abort(404);
        }
        $equipment->loadMissing(['users' => function ($q) {
            $q->where('equipment_users.status', 1);
        }]);

        $isAvailable = $equipment->status == 1
            && ! $equipment->users->where('pivot.status', 1)->count();

        return view('home.show', compact('equipment', 'isAvailable'));
    }

    /**
     * Form tạo phiếu mượn thiết bị (user đã đăng nhập).
     */
    public function createBorrow(Equipment $equipment): View|RedirectResponse
    {
        // Kiểm tra thiết bị có thể mượn không
        $isBorrowed = EquipmentUser::where('equipment_id', $equipment->id)
            ->where('status', 1)->exists();

        if ($equipment->status != 1 || $isBorrowed || $equipment->available == 0) {
            return redirect()->route('home')->with('error', 'Thiết bị này hiện không thể mượn.');
        }

        return view('home.borrow', compact('equipment'));
    }

    /**
     * Lưu phiếu mượn thiết bị.
     */
    public function storeBorrow(Request $request, Equipment $equipment): RedirectResponse
    {
        $validated = $request->validate([
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        // Kiểm tra lại trước khi lưu
        $isBorrowed = EquipmentUser::where('equipment_id', $equipment->id)
            ->where('status', 1)->exists();

        if ($equipment->status != 1 || $isBorrowed) {
            return redirect()->route('home')->with('error', 'Thiết bị này hiện không thể mượn.');
        }

        // Kiểm tra user đang mượn thiết bị này chưa
        $alreadyBorrowing = EquipmentUser::where('user_id', Auth::id())
            ->where('equipment_id', $equipment->id)
            ->where('status', 1)
            ->exists();

        if ($alreadyBorrowing) {
            return back()->with('error', 'Bạn đang mượn thiết bị này rồi!');
        }

        EquipmentUser::create([
            'user_id' => Auth::id(),
            'equipment_id' => $equipment->id,
            'ngaymuon' => now(),
            'status' => 1,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('home')->with('success', 'Tạo phiếu mượn thành công! Chúc bạn sử dụng tốt.');
    }
}
